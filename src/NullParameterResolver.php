<?php

declare(strict_types=1);

namespace Bogosoft\Reflection;

use Psr\Container\ContainerInterface as IContainer;
use ReflectionParameter;

/**
 * An implementation of the {@see IParameterResolver} contract that will not
 * resolve a reflection parameter under any circumstances.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Reflection
 */
final class NullParameterResolver implements IParameterResolver
{
    static function __set_state($data)
    {
        return new NullParameterResolver();
    }

    /**
     * @inheritDoc
     */
    function resolve(ReflectionParameter $rp, IContainer $services, &$result): bool
    {
        return false;
    }
}
