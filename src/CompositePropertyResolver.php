<?php

declare(strict_types=1);

namespace Bogosoft\Reflection;

use Psr\Container\ContainerInterface as IContainer;
use ReflectionProperty;

/**
 * A composite implementation of the {@see IPropertyResolver} contract
 * that allows multiple property resolvers to behave as if they were a single
 * property resolver.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Reflection
 */
final class CompositePropertyResolver implements IPropertyResolver
{
    static function __set_state($data)
    {
        return new CompositePropertyResolver($data['resolvers']);
    }

    /** @var IPropertyResolver[] */
    private array $resolvers;

    /**
     * Create a new composite property resolver.
     *
     * @param IPropertyResolver ...$resolvers Zero or more property resolvers
     *                                        from which the composite will be
     *                                        formed.
     */
    function __construct(IPropertyResolver ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    /**
     * Add an additional property resolver to the current composite property
     * resolver.
     *
     * @param IPropertyResolver $resolver
     */
    function add(IPropertyResolver $resolver): void
    {
        $this->resolvers[] = $resolver;
    }

    /**
     * @inheritDoc
     */
    function resolveProperty(ReflectionProperty $rp, IContainer $services, &$result): bool
    {
        foreach ($this->resolvers as $resolver)
            if ($resolver->resolveProperty($rp, $services, $result))
                return true;

        return false;
    }
}