<?php

declare(strict_types=1);

namespace Goksagun\PaginationBundle\Pagination;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Goksagun\PaginationBundle\Pagination\Utils\Pager;

class Paginator implements PaginatorInterface
{

    private DoctrineQueryBuilder $queryBuilder;
    private int $currentPage;
    private int $pageSize;
    private \Traversable|\ArrayIterator $results;
    private int $numResults;

    public function __construct(DoctrineQueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function paginate(int $page, int $pageSize = self::DESKTOP_PAGE_SIZE): PaginatorInterface
    {
        $this->currentPage = max(1, $page);
        $this->pageSize = $pageSize;
        $firstResult = ($this->currentPage - 1) * $this->pageSize;

        $query = $this->queryBuilder
            ->setFirstResult($firstResult)
            ->setMaxResults($this->pageSize)
            ->getQuery();

        if (0 === \count($this->queryBuilder->getDQLPart('join'))) {
            $query->setHint(CountWalker::HINT_DISTINCT, false);
        }

        $paginator = new DoctrinePaginator($query, true);

        $useOutputWalkers = \count($this->queryBuilder->getDQLPart('having') ?: []) > 0;
        $paginator->setUseOutputWalkers($useOutputWalkers);

        $this->results = $paginator->getIterator();
        $this->numResults = $paginator->count();

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getLastPage(): int
    {
        return (int)ceil($this->numResults / $this->pageSize);
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->getLastPage();
    }

    public function getNextPage(): int
    {
        return min($this->getLastPage(), $this->currentPage + 1);
    }

    public function hasToPaginate(): bool
    {
        return $this->numResults > $this->pageSize;
    }

    public function getNumResults(): int
    {
        return $this->numResults;
    }

    public function getResults(): \Traversable
    {
        return $this->results;
    }

    public function getTotal(): int
    {
        return $this->results->count();
    }

    public function getVisiblePages(): array
    {
        $pager = new Pager($this->getLastPage(), $this->currentPage);

        return $pager->prepare();
    }
}