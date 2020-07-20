<?php

declare(strict_types=1);

namespace Tests;

use Psr\Container\ContainerInterface as IContainer;
use Psr\Container\NotFoundExceptionInterface as INotFoundInterface;
use RuntimeException;

class SingleRegistrationContainer implements IContainer
{
    private string $name;

    /** @var mixed */
    private $service;

    function __construct(string $name, $service)
    {
        $this->name    = $name;
        $this->service = $service;
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        if ($this->has($id))
            return $this->service;

        throw new class extends RuntimeException implements INotFoundInterface
        {
        };
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return $id === $this->name;
    }
}
