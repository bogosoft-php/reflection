<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Reflection\DefaultValueParameterResolver;
use PHPUnit\Framework\TestCase;
use ReflectionFunction;

class DefaultValueParameterResolverTest extends TestCase
{
    function testCannotResolveWhenParameterHasNoDefaultValue(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $rf = new ReflectionFunction(function(string $name): string
        {
            return $name;
        });

        $rp = $rf->getParameters()[0];

        $resolver = new DefaultValueParameterResolver();

        $container = new EmptyContainer();

        $this->assertFalse($resolver->resolveParameter($rp, $container, $result));
    }

    function testCanResolveWhenParameterHasDefaultValue(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $rf = new ReflectionFunction(function(string $name = null): ?string
        {
            return $name;
        });

        $rp = $rf->getParameters()[0];

        $resolver = new DefaultValueParameterResolver();

        $container = new EmptyContainer();

        $this->assertTrue($resolver->resolveParameter($rp, $container, $result));

        $this->assertNull($result);
    }
}
