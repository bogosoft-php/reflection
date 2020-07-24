<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Tests;

use Bogosoft\Reflection\TypedResolver;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface as IContainerException;
use Psr\Container\ContainerInterface as IContainer;
use Psr\Container\NotFoundExceptionInterface as INotFoundException;
use ReflectionClass;
use ReflectionFunction;
use RuntimeException;
use stdClass;
use Throwable;

class TypedResolverTest extends TestCase
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

        $resolver = new TypedResolver();

        /** @var Throwable $exception */
        $exception = null;

        try
        {
            $this->assertFalse($resolver->resolveParameter($rp, $container, $result));
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

        $resolver = new TypedResolver();

        $this->assertFalse($resolver->resolveParameter($rp, $container, $result));
    }

    function testCannotResolveWhenPropertyIsNotTyped(): void
    {
        $test = new class
        {
            /** @var mixed */
            public $greeting;
        };

        $rc = new ReflectionClass($test);

        $rp = $rc->getProperties()[0];

        $container = new class implements IContainer
        {
            public function get($id)
            {
                return 'Hello, World!';
            }

            public function has($id)
            {
                return true;
            }
        };

        $resolver = new TypedResolver();

        $this->assertFalse($resolver->resolveProperty($rp, $container, $result));
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

        $resolver = new TypedResolver();

        $this->assertTrue($resolver->resolveParameter($rp, $container, $result));

        $this->assertEquals($expected, $result->greeting);
    }

    function testCanResolveWhenPropertyIsTypedAndRegistered(): void
    {
        $expected = 'Hello, World!';

        $test = new class
        {
            public string $greeting;
        };

        $rc = new ReflectionClass($test);

        $rp = $rc->getProperties()[0];

        $container = new SingleRegistrationContainer('string', $expected);

        $resolver = new TypedResolver();

        $this->assertTrue($resolver->resolveProperty($rp, $container, $actual));

        $this->assertEquals($expected, $actual);
    }
}
