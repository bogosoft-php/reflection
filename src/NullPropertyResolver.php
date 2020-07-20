<?php

declare(strict_types=1);

namespace Bogosoft\Reflection;

use Psr\Container\ContainerInterface as IContainer;
use ReflectionProperty;

/**
 * An implementation of the {@see IPropertyResolver} contract that will not
 * resolve a reflection property under any circumstance.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Reflection
 */
final class NullPropertyResolver implements IPropertyResolver
{
    static function __set_state($data)
    {
        return new NullPropertyResolver();
    }

    /**
     * @inheritDoc
     */
    function resolve(ReflectionProperty $rp, IContainer $services, &$result): bool
    {
        return false;
    }
}
