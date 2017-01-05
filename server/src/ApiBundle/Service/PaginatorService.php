<?php

namespace ApiBundle\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorService extends Paginator
{
    /**
     * @var integer
     */
    protected $page;

    /**
     * @var integer
     */
    protected $limit;

    /**
     * @var integer
     */
    protected $totalRecord;

    /**
     * @var integer
     */
    protected $startRecord;

    /**
     * @var integer
     */
    protected $endRecord;

    /**
     * @var integer
     */
    protected $totalPage;

    /**
     * @var integer
     */
    protected $prevPage;

    /**
     * @var integer
     */
    protected $nextPage;

    /**
     * __construct
     *
     * @param Dql  $dql
     * @param integer $page
     * @param integer $limit
     */
    public function __construct($dql, $page = 1, $limit = 15)
    {
        parent::__construct($dql);

        $this->page = $page;
        $this->limit = ($limit <= 100)?$limit:100;

        $this->getQuery()
            ->setFirstResult($this->limit * ($this->page - 1))
            ->setMaxResults($this->limit);

        //Calculate
        $iteratorsRecord = $this->getIterator()->count();
        $this->totalRecord = $this->count();
        $this->totalPage = ceil($this->totalRecord / $this->limit);
        $this->startRecord = (($this->page - 1) * $this->limit) + 1;
        $this->endRecord = $this->startRecord + $iteratorsRecord - 1;
        $this->prevPage = ($this->page - 1 > 0) ? $this->page - 1 : 1;
        $this->nextPage = ($this->page + 1 < $this->totalPage) ? $this->page + 1 : $this->totalPage;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getTotalRecord()
    {
        return $this->totalRecord;
    }

    /**
     * @return int
     */
    public function getStartRecord()
    {
        return $this->startRecord;
    }

    /**
     * @return int
     */
    public function getEndRecord()
    {
        return $this->endRecord;
    }

    /**
     * @return int
     */
    public function getTotalPage()
    {
        return $this->totalPage;
    }

    /**
     * @return int
     */
    public function getPrevPage()
    {
        return $this->prevPage;
    }

    /**
     * @return int
     */
    public function getNextPage()
    {
        return $this->nextPage;
    }
}
