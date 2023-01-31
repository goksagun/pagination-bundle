<?php

namespace Goksagun\PaginationBundle\Pagination;

interface PaginatorInterface
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them under parameters section in config/services.yaml file.
     *
     * See https://symfony.com/doc/current/best_practices.html#use-constants-to-define-options-that-rarely-change
     */
    public const DESKTOP_PAGE_SIZE = 10;
    public const SMARTPHONE_PAGE_SIZE = 5;

    public function paginate(int $page, int $pageSize = self::DESKTOP_PAGE_SIZE): self;

    public function getCurrentPage(): int;

    public function getLastPage(): int;

    public function getPageSize(): int;

    public function hasPreviousPage(): bool;

    public function getPreviousPage(): int;

    public function hasNextPage(): bool;

    public function getNextPage(): int;

    public function hasToPaginate(): bool;

    public function getNumResults(): int;

    public function getResults(): \Traversable;

    public function getTotal(): int;

    public function getVisiblePages(): array;
}