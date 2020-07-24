<?php

declare(strict_types=1);

namespace Bogosoft\Reflection;

use Psr\Container\ContainerInterface as IContainer;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;

/**
 * An implementation of the {@see IParameterResolver}
 * and {@see IPropertyResolver} contracts  to resolve a service by keying off
 * of the name of the type of a given parameter or property.
 *
 * This resolver is only successful when ...
 *
 * - The given parameter or property has been type-hinted.
 * - A corresponding service can be resolved from the given container by the
 *   name of the type of the given parameter or property.
 *
 * @package Bogosoft\Reflection
 */
class TypedResolver implements IParameterResolver, IPropertyResolver
{
    static function __set_state($an_array)
    {
        return new TypedResolver();
    }

    /**
     * @inheritDoc
     */
    function resolveParameter(ReflectionParameter $rp, IContainer $services, &$result): bool
    {
        if (
            null === ($type = $rp->getType())
            || !($type instanceof ReflectionNamedType)
            || !$services->has($name = $type->getName())
        )
            return false;

        $result = $services->get($name);

        return true;
    }

    /**
     * @inheritDoc
     */
    function resolveProperty(ReflectionProperty $rp, IContainer $services, &$result): bool
    {
        if (
            null !== ($type = $rp->getType())
            && $type instanceof ReflectionNamedType
            && $services->has($name = $type->getName())
        )
        {
            $result = $services->get($name);

            return true;
        }

        return false;
    }
}