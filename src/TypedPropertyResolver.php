<?php

declare(strict_types=1);

namespace Bogosoft\Reflection;

use Psr\Container\ContainerInterface as IContainer;
use ReflectionNamedType;
use ReflectionProperty;

/**
 * A strategy for resolving a class property by reflecting upon its type
 * hint.
 *
 * @package Bogosoft\Reflection
 */
class TypedPropertyResolver implements IPropertyResolver
{
    static function __set_state($data)
    {
        return new TypedPropertyResolver();
    }

    /**
     * @inheritDoc
     */
    function resolve(ReflectionProperty $rp, IContainer $services, &$result): bool
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
