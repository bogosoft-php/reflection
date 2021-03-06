<?php

namespace Bogosoft\Reflection;

use Psr\Container\ContainerInterface as IContainer;
use ReflectionParameter;

/**
 * Represents a container-assisted strategy for resolving a
 * {@see ReflectionParameter}.
 *
 * @package Bogosoft\Reflection
 */
interface IParameterResolver
{
    /**
     * Attempt to resolve a given reflection parameter.
     *
     * @param  ReflectionParameter $rp       A function or method parameter.
     * @param  IContainer          $services A service resolution container.
     * @param  mixed|null          $result   The result of resolving the value
     *                                       of the given parameter.
     * @return bool                          A value indicating whether or not
     *                                       the value of the given parameter
     *                                       was successfully resolved.
     */
    function resolveParameter(ReflectionParameter $rp, IContainer $services, &$result): bool;
}
