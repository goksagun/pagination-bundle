<?php

namespace Goksagun\PaginationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GoksagunPaginationBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}