<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ApiBundle\Service\PaginatorService;

/**
 * This custom Doctrine repository is empty because so far we don't need any custom
 * method to query for application user information. But it's always a good practice
 * to define a custom repository that will be used when the application grows.
 * See http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes
 *
 */
class UserRepository extends EntityRepository
{
    public function getUserByResetToken($token)
    {
        return $this->createQueryBuilder('u')
            ->where('u.resetToken = :token AND u.resetTimeout > :now')
            ->setParameter('token', $token)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get list by pagination
     *
     * @param integer $page
     * @param integer $limit
     * @param string $orderBy Order By Field. If prefix is '-'=DESC, not '-'=ASC
     * @param array $filter Filter for search
     * @return PaginatorService
     */
    public function getListPagination($page=1, $limit=10, $orderBy='createdAt', array $filter)
    {
        $orderDir='ASC';
        if(strpos($orderBy, '-') !== false) {
            $orderDir='DESC';
        }
        $orderBy = preg_replace('/[\+|\-]/i', '', $orderBy);

        $query = $this->createQueryBuilder('u')
            ->orderBy('u.'.$orderBy, $orderDir);

        if(!empty($filter)) {
            foreach ($filter as $field => $value) {
                if($value == 'true' || $value == 'false') {
                    $query->andWhere('u.'.$field.' = :value');
                    $query->setParameter('value', filter_var($value, FILTER_VALIDATE_BOOLEAN));
                } else {
                    $query->andWhere('u.'.$field.' LIKE :value');
                    $query->setParameter('value', '%'.$value.'%');
                }
            }
        }

        $dql = $query->getQuery();

        return new PaginatorService($dql, $page, $limit);
    }
}
