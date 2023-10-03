<?php

namespace Core;

class Paginator
{
    private $limit;
    public $currentPage;
    public $totalResults;
    private $slug;

    public function __construct($limit, $currentPage, $totalResults, $slug)
    {
        $this->limit = $limit;
        $this->currentPage = $currentPage;
        $this->totalResults = $totalResults;
        $this->slug = $slug;
    }

    public function getOffset()
    {
        return ($this->currentPage - 1) * $this->limit;
    }

    public function getLast()
    {
        return ($this->totalResults) * $this->limit;
    }

    public function getPrevious()
    {
        $prev = $this->currentPage - 1;
        if($prev < 1) {
            return false;
        }
        return $prev;
    }

    public function getNext()
    {
        if($this->currentPage >= $this->getTotalPages()) {
            return false;
        }
        return $this->currentPage + 1;
    }

    public function getQueryParams()
    {
        if($_SERVER['QUERY_STRING'] !== '') {
            return '?' . $_SERVER['QUERY_STRING'];
        }

        return '';
    }

    public function getTotalPages()
    {
        return ceil($this->totalResults / $this->limit);
    }

    public function getRange()
    {
        // range of num links to show
        $range = 2;

        // display links to 'range of pages' around 'current page'
        $initial_num = $this->currentPage - $range;
        $condition_limit_num = ($this->currentPage + $range) + 1;

        $nums = [];
        for ($x = $initial_num; $x < $condition_limit_num; $x++) {
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if (($x > 0) && ($x <= $this->getTotalPages())) {
                $nums[] = $x;
            }
        }

        return $nums;
    }

    public function getSlug()
    {
        return $this->slug;
    }
}