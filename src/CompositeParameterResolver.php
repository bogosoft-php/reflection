<?php

declare(strict_types=1);

namespace Bogosoft\Reflection;

use Psr\Container\ContainerInterface as IContainer;
use ReflectionParameter;

/**
 * A composite implementation of the {@see IParameterResolver} contract
 * that allows multiple parameter resolvers to behave as if they were a
 * single parameter resolver.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Reflection
 */
final class CompositeParameterResolver implements IParameterResolver
{
    static function __set_state($data)
    {
        return new CompositeParameterResolver($data['resolvers']);
    }

    /** @var IParameterResolver[]  */
    private array $resolvers;

    /**
     * Create a new composite parameter resolver.
     *
     * @param IParameterResolver ...$resolvers Zero or more parameter
     *                                         resolvers from which the
     *                                         composite will be formed.
     */
    function __construct(IParameterResolver ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    /**
     * Add an additional parameter resolver to the current composite parameter
     * resolver.
     *
     * @param IParameterResolver $resolver A parameter resolver.
     */
    function add(IParameterResolver $resolver): void
    {
        $this->resolvers[] = $resolver;
    }

    /**
     * @inheritDoc
     */
    function resolveParameter(ReflectionParameter $rp, IContainer $services, &$result): bool
    {
        foreach ($this->resolvers as $resolver)
            if ($resolver->resolveParameter($rp, $services, $result))
                return true;

        return false;
    }
}
