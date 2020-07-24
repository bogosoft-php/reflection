<?php

namespace Bogosoft\Reflection;

use Psr\Container\ContainerInterface as IContainer;
use ReflectionProperty;

/**
 * Represents a PSR-11 container-assisted strategy for resolving a
 * {@see ReflectionProperty}.
 *
 * @package Bogosoft\Reflection
 */
interface IPropertyResolver
{
    /**
     * Attempt to resolve a given property.
     *
     * @param  ReflectionProperty $rp       A class property.
     * @param  IContainer         $services A service resolution container.
     * @param  mixed              $result   The result of resolving the value
     *                                      of a given property.
     * @return bool                         A value indicating whether or not
     *                                      the given property was successfully
     *                                      resolved.
     */
    function resolveProperty(ReflectionProperty $rp, IContainer $services, &$result): bool;
}
