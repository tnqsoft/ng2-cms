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
        $arguments = json_decode($request->getContent(), true);
        if (null === $arguments) {
            $arguments = array();
        }

        $validator = $this->get('app.validator.user');
        if (false === $validator->changePasswordValidate($arguments)) {
            throw new HttpException(400, json_encode($validator->getErrorList()));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $passwordEncoder = $this->get('security.password_encoder');
        $password = $passwordEncoder->encodePassword($user, $arguments['newPassword']);
        $user->setPassword($password);
        $em->persist($user);
        $em->flush();

        return;
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
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_USER_ADD')")
     * @ApiDoc(
     *  description="Add User",
     *  section="User",
     *  parameters={
     *      {"name"="title", "dataType"="string", "required"=true, "description"="Title"},
     *      {"name"="description", "dataType"="string", "required"=true, "description"="Description"},
     *      {"name"="isActive", "dataType"="boolean", "required"=true, "description"="Active"},
     *      {"name"="role", "dataType"="textarea", "required"=true, "description"="List Roles"}
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
        //Get and Validate Input Data
        $arguments = $this->getAndValidateInputData($request);
        //Insert Database
        $record = new User();
        $record->setUsername($arguments['username']);
        $record->setEmail($arguments['email']);
        $record->setIsActive($arguments['isActive']);
        $passwordEncoder = $this->get('security.password_encoder');
        $password = $passwordEncoder->encodePassword($record, $arguments['password']);
        $record->setPassword($password);

        $em = $this->getDoctrine()->getManager();
        $em->persist($record);
        $em->flush();

        $response = new Response(null, Response::HTTP_CREATED);

        return $response;
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
     * Update A User Group.
     *
     * @Put("/{id}", requirements={"id" = "\d+"})
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_USERGROUP_EDIT')")
     * @ApiDoc(
     *  description="Update User Group",
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
     *      {"name"="groupId", "dataType"="integer", "required"=true, "description"="Group Id"}
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
        //Get and Validate Input Data
        $arguments = $this->getAndValidateInputData($request);
        //Insert Database
        $record = $this->getRecordById(User::class, $id);
        $record->setUsername($arguments['username']);
        $record->setEmail($arguments['email']);
        $record->setIsActive($arguments['isActive']);

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

        $response = new Response('Xóa thành công bản ghi có id là '.$id, Response::HTTP_NO_CONTENT);

        return $response;
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
            'group' => array(
                'id' => $record->getGroup()->getId(),
                'title' => $record->getGroup()->getTitle(),
                'description' => $record->getGroup()->getDescription(),
            ),
            'isActive' => $record->getIsActive(),
            'createdAt' => ($record->getCreatedAt() !== null) ? $record->getCreatedAt()->format('Y-m-d\TH:i:s') : null,
            'updatedAt' => ($record->getUpdatedAt() !== null) ? $record->getUpdatedAt()->format('Y-m-d\TH:i:s') : null,
        );
    }

    /**
     * Get and Validate Input Data.
     *
     * @param Request $request
     *
     * @return array
     */
    public function getAndValidateInputData(Request $request)
    {
        //Get Input Data
        $arguments = json_decode($request->getContent(), true);
        if (null === $arguments) {
            $arguments = array();
        }
        //Validate Input Data
        $validator = $this->get('app.validator.user');
        if (false === $validator->addAndUpdateValidate($arguments)) {
            throw new HttpException(400, json_encode($validator->getErrorList()));
        }

        return $arguments;
    }

}
