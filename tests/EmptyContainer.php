<?php

declare(strict_types=1);

namespace Tests;

use Psr\Container\ContainerInterface as IContainer;
use Psr\Container\NotFoundExceptionInterface as INotFoundException;
use RuntimeException;

final class EmptyContainer implements IContainer
{
    /**
     * @inheritDoc
     */
    public function get($id)
    {
        throw new class extends RuntimeException implements INotFoundException
        {
        };
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return false;
    }
}
