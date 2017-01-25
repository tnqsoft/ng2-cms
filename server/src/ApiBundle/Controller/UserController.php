<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Entity\User;

/**
 * UserController.
 */
class UserController extends BaseRestController
{
    /**
     * Authenticate user and return JWT token.
     *
     * @Post("/login")
     *
     * @ApiDoc(
     *  description="Login",
     *  section="User",
     *  requirements={
     *    {
     *      "name"="username",
     *      "dataType"="string",
     *      "requirement"="\w+",
     *      "description"="Username"
     *    },{
     *      "name"="password",
     *      "dataType"="string",
     *      "requirement"="\w+",
     *      "description"="Password"
     *    }
     *  },
     *  parameters={
     *      {"name"="remember_me", "dataType"="boolean", "required"=true, "description"="Remember"}
     *  },
     *  statusCodes={
     *    200="Returned when successful",
     *    401="Returned when not have token or token expired",
     *    400="Returned if not validated",
     *  }
     * )
     *
     * @return array
     */
    public function loginAction(Request $request)
    {
        // This controller is only to display in API Doc
        throw new \RuntimeException('This should never be reached!');
    }

    /**
     * Logout.
     *
     * @Post("/logout")
     * @View(statusCode=204)
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="Logout",
     *  section="User",
     *  statusCodes={
     *    200="Returned when successful",
     *    401="Returned when not have token or token expired",
     *    400="Returned if not validated",
     *  }
     * )
     *
     * @return array
     */
    public function logoutAction(Request $request)
    {
        // This controller is only to display in API Doc
    }

    /**
     * User change password.
     *
     * @Put("/change_password")
     * @View(statusCode=204)
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="User change password",
     *  section="User",
     *  parameters={
     *      {"name"="oldPassword", "dataType"="string", "required"=true, "description"="Old password"},
     *      {"name"="newPassword", "dataType"="string", "required"=true, "description"="New password"}
     *  },
     *  statusCodes={
     *    200="Returned when successful",
     *    401="Returned when not have token or token expired",
     *    400="Returned if not validated",
     *  }
     * )
     *
     * @return array
     */
    public function changePasswordAction(Request $request)
    {
        $arguments = $this->getValidator($request, 'changePasswordValidate', new User());
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $passwordEncoder = $this->get('security.password_encoder');
        $password = $passwordEncoder->encodePassword($user, $arguments['newPassword']);
        $user->setPassword($password);
        $em->persist($user);
        $em->flush();
    }

    /**
     * Get Current User
     *
     * @Get("/me")
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="Get Current User",
     *  section="User",
     *  parameters={
     *  },
     *  statusCodes={
     *    200="Returned when successful",
     *    401="Returned when not have token or token expired",
     *    400="Returned if not validated",
     *  }
     * )
     *
     * @return array
     */
    public function getCurrentUserAction(Request $request)
    {
        return $this->getUser();
    }

    /**
     * Update current user
     *
     * @Put("/me")
     * @View(statusCode=204)
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="Update current user",
     *  section="User",
     *  parameters={
     *      {"name"="email", "dataType"="string", "required"=true, "description"="Email"}
     *  },
     *  statusCodes={
     *    200="Returned when successful",
     *    401="Returned when not have token or token expired",
     *    400="Returned if not validated",
     *  }
     * )
     *
     * @return array
     */
    public function updateCurrentUserAction(Request $request)
    {
        $user = $this->getUser();
        $arguments = $this->getValidator($request, 'updateProfileValidate', $user);
        $em = $this->getDoctrine()->getManager();
        $user->setEmail($arguments['email']);
        $em->persist($user);
        $em->flush();
    }

    //CRUD API------------------------------------------------------------------------------

    /**
     * Get User List.
     *
     * @Get("")
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_USER_VIEW')")
     * @ApiDoc(
     *  description="Get User List",
     *  section="User",
     *  filters={
     *      {"name"="page", "dataType"="integer", "default"=1},
     *      {"name"="limit", "dataType"="integer", "default"=15},
     *      {"name"="orderBy", "dataType"="string", "default"="createdAt", "description"="Default is createdAt. If prefix is '-'=DESC, not '-'=ASC"},
     *      {"name"="filter", "dataType"="string", "default"=""}
     *  },
     *  statusCodes={
     *    200="Returned when get list successful",
     *    401="Returned when not have token or token expired",
     *    403="Returned when not permission accepting",
     *    405="Returned when used wrong method call",
     *    500="Returned when Server error",
     *    501="Returned when can not implemented",
     *    503="Returned when service unavailable"
     *  }
     * )
     *
     * @return array
     */
    public function getUsersAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 15);
        $orderBy = $request->query->get('orderBy', 'createdAt');
        $filter = $request->query->get('filter', '{}');
        $filter = json_decode($filter, true);
        if (null === $filter) {
            $filter = array();
        }

        $repository = $this->getRepository(User::class);
        $paginator = $repository->getListPagination($page, $limit, $orderBy, $filter);

        return $this->getResponseForList($paginator);
    }

    /**
     * Add User.
     *
     * @Post("")
     * @View(statusCode=204)
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_USER_ADD')")
     * @ApiDoc(
     *  description="Add User",
     *  section="User",
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="Title"},
     *      {"name"="password", "dataType"="string", "required"=false, "description"="Password"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
     *      {"name"="isActive", "dataType"="boolean", "required"=true, "description"="Active"},
     *      {"name"="roles", "dataType"="array", "required"=true, "description"="List Roles"}
     *  },
     *  statusCodes={
     *    201="Returned when create successful",
     *    400="Returned if not validated",
     *    401="Returned when not have token or token expired",
     *    403="Returned when not permission accepting",
     *    405="Returned when used wrong method call",
     *    500="Returned when Server error",
     *    501="Returned when can not implemented",
     *    503="Returned when service unavailable"
     *  }
     * )
     *
     * @return array
     */
    public function createUserAction(Request $request)
    {
        // Create Object
        $record = new User();
        //Get and Validate Input Data
        $arguments = $this->getValidator($request, 'addAndUpdateValidate', $record);
        //Insert Database
        $record->setUsername($arguments['username']);
        $record->setEmail($arguments['email']);
        $record->setIsActive($arguments['isActive']);
        $passwordEncoder = $this->get('security.password_encoder');
        $password = $passwordEncoder->encodePassword($record, $arguments['password']);
        $record->setPassword($password);
        $record->setRoles($arguments['roles']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($record);
        $em->flush();
    }

    /**
     * Get A User.
     *
     * @Get("/{id}", requirements={"id" = "\d+"})
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_USER_DETAIL')")
     * @ApiDoc(
     *  description="Get A User",
     *  section="User",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="User Id"
     *      }
     *  },
     *  statusCodes={
     *    200="Returned when get detail successful",
     *    401="Returned when not have token or token expired",
     *    403="Returned when not permission accepting",
     *    404="Returned when can not find item",
     *    405="Returned when used wrong method call",
     *    500="Returned when Server error",
     *    501="Returned when can not implemented",
     *    503="Returned when service unavailable"
     *  }
     * )
     *
     * @return array
     */
    public function getUserAction(Request $request, $id)
    {
        $record = $this->getRecordById(User::class, $id);

        return $this->tranformRecord($record);
    }

    /**
     * Update A User.
     *
     * @Put("/{id}", requirements={"id" = "\d+"})
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_USERGROUP_EDIT')")
     * @ApiDoc(
     *  description="Update User",
     *  section="User",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Group Id"
     *      }
     *  },
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="Title"},
     *      {"name"="password", "dataType"="string", "required"=false, "description"="Password"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="Description"},
     *      {"name"="isActive", "dataType"="boolean", "required"=true, "description"="Active"},
     *      {"name"="roles", "dataType"="array", "required"=true, "description"="List Roles"}
     *  },
     *  statusCodes={
     *    204="Returned when update successful",
     *    400="Returned if not validated",
     *    401="Returned when not have token or token expired",
     *    403="Returned when not permission accepting",
     *    404="Returned when can not find item",
     *    405="Returned when used wrong method call",
     *    500="Returned when Server error",
     *    501="Returned when can not implemented",
     *    503="Returned when service unavailable"
     *  }
     * )
     *
     * @return array
     */
    public function updateUserAction(Request $request, $id)
    {
        //Get Current Object
        $record = $this->getRecordById(User::class, $id);
        //Get and Validate Input Data
        $arguments = $this->getValidator($request, 'addAndUpdateValidate', $record);
        //Insert Database
        $record->setUsername($arguments['username']);
        $record->setEmail($arguments['email']);
        $record->setIsActive($arguments['isActive']);
        $record->setRoles($arguments['roles']);

        if (!empty($arguments['password'])) {
            $passwordEncoder = $this->get('security.password_encoder');
            $password = $passwordEncoder->encodePassword($record, $arguments['password']);
            $record->setPassword($password);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($record);
        $em->flush();

        $response = new Response(null, Response::HTTP_OK);

        return $response;
    }

    /**
     * Delete A User.
     *
     * @Delete("/{id}", requirements={"id" = "\d+"})
     * @View(statusCode=204)
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_USER_DELETE')")
     * @ApiDoc(
     *  description="Delete User",
     *  section="User",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="User Id"
     *      }
     *  },
     *  statusCodes={
     *    204="Returned when delete successful",
     *    401="Returned when not have token or token expired",
     *    403="Returned when not permission accepting",
     *    404="Returned when can not find item",
     *    405="Returned when used wrong method call",
     *    500="Returned when Server error",
     *    501="Returned when can not implemented",
     *    503="Returned when service unavailable"
     *  }
     * )
     *
     * @return array
     */
    public function deleteUserAction(Request $request, $id)
    {
        $record = $this->getRecordById(User::class, $id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($record);
        $em->flush();
    }

    /**
     * Tranform Record.
     *
     * @param mixed $record
     *
     * @return array
     */
    public function tranformRecord($record)
    {
        return array(
            'id' => $record->getId(),
            'username' => $record->getUsername(),
            'email' => $record->getEmail(),
            'roles' => $record->getRoles(),
            'isActive' => $record->getIsActive(),
            'createdAt' => ($record->getCreatedAt() !== null) ? $record->getCreatedAt()->format('Y-m-d\TH:i:s') : null,
            'updatedAt' => ($record->getUpdatedAt() !== null) ? $record->getUpdatedAt()->format('Y-m-d\TH:i:s') : null,
        );
    }

    /**
     * Get and Validate Input Data.
     *
     * @param Request $request
     * @param string $functionName
     * @param User $currentObject
     * @return array
     */
    public function getValidator(Request $request, $functionName, $currentObject)
    {
        //Get Input Data
        $arguments = json_decode($request->getContent(), true);
        if (null === $arguments) {
            $arguments = array();
        }

        $arguments['isActive'] = filter_var($this->getArrayValue('isActive', $arguments), FILTER_VALIDATE_BOOLEAN);

        //Validate Input Data
        $validator = $this->get('app.validator.user');
        $validator->setCurrentObject($currentObject);
        if (false === call_user_func_array(array($validator, $functionName), array($arguments))) {
            throw new HttpException(400, json_encode($validator->getErrorList()));
        }

        return $arguments;
    }

}
