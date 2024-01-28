<?php

declare(strict_types=1);

namespace Goksagun\PaginationBundle\Pagination\Utils;

readonly class Pager
{
    public function __construct(private int $lastPage, private int $currentPage)
    {
    }

    public function prepare(): array
    {
        $visiblePages = [];

        if ($this->lastPage == 0) {
            return $visiblePages;
        }

        if (1 < $this->lastPage && $this->lastPage < 6) {
            return range(1, $this->lastPage);
        }

        for ($page = 1; $page <= $this->lastPage; $page++) {
            if ($page < 3) {
                $visiblePages[] = $page;
                continue;
            }

            if ($page > $this->lastPage - 2) {
                $visiblePages[] = $page;
                continue;
            }

            if ($page == $this->currentPage) {
                $visiblePages[] = $page;
            }

            if ($page == 3 && $this->currentPage > 3) {
                $visiblePages[] = 0;
            } elseif ($page == $this->lastPage - 2 && $this->currentPage < $this->lastPage - 2) {
                $visiblePages[] = 0;
            }
        }

        return $visiblePages;
    }
}