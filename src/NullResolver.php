<?php

declare(strict_types=1);

namespace Bogosoft\Reflection;

use Psr\Container\ContainerInterface as IContainer;
use ReflectionParameter;
use ReflectionProperty;

/**
 * An implementation of the {@see IParameterResolver} and
 * {@see IPropertyResolver} contracts that will never resolve under any
 * circumstances.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Reflection
 */
final class NullResolver implements IParameterResolver, IPropertyResolver
{
    /**
     * @inheritDoc
     */
    function resolveParameter(ReflectionParameter $rp, IContainer $services, &$result): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    function resolveProperty(ReflectionProperty $rp, IContainer $services, &$result): bool
    {
        return false;
    }
}
