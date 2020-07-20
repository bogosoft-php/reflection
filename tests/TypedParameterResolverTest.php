<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Reflection\TypedParameterResolver;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface as IContainerException;
use Psr\Container\ContainerInterface as IContainer;
use Psr\Container\NotFoundExceptionInterface as INotFoundException;
use ReflectionFunction;
use RuntimeException;
use stdClass;
use Throwable;

class TypedParameterResolverTest extends TestCase
{
    function testCannotResolveWhenParameterIsNotTyped(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $rf = new ReflectionFunction(function($data): void
        {
        });

        $rp = $rf->getParameters()[0];

        $container = new class implements IContainer
        {
            public function get($id)
            {
                throw new class extends RuntimeException implements INotFoundException {
                };
            }

            public function has($id)
            {
                throw new class extends RuntimeException implements IContainerException
                {
                };
            }
        };

        $resolver = new TypedParameterResolver();

        /** @var Throwable $exception */
        $exception = null;

        try
        {
            $this->assertFalse($resolver->resolve($rp, $container, $result));
        }
        catch (Throwable $t)
        {
            $exception = $t;
        }

        $this->assertNull($exception);
    }

    function testCannotResolveWhenParameterTypeNotRegistered(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $rf = new ReflectionFunction(function(stdClass $data): void
        {
        });

        $rp = $rf->getParameters()[0];

        $container = new EmptyContainer();

        $resolver = new TypedParameterResolver();

        $this->assertFalse($resolver->resolve($rp, $container, $result));
    }

    function testCanResolveWhenParameterTypeIsRegistered(): void
    {
        $expected = 'Hello, World!';

        /** @noinspection PhpUnhandledExceptionInspection */
        $rf = new ReflectionFunction(function(stdClass $data): string
        {
            return $data->greeting;
        });

        $rp = $rf->getParameters()[0];

        $service = new stdClass();

        $service->greeting = $expected;

        $container = new SingleRegistrationContainer(stdClass::class, $service);

        $resolver = new TypedParameterResolver();

        $this->assertTrue($resolver->resolve($rp, $container, $result));

        $this->assertEquals($expected, $result->greeting);
    }
}
