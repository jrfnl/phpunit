<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Event\TestDouble;

use function sprintf;
use PHPUnit\Event\Event;
use PHPUnit\Event\Telemetry;

/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */
final class MockObjectCreatedFromWsdl implements Event
{
    private Telemetry\Info $telemetryInfo;

    private string $wsdlFile;

    /**
     * @psalm-var class-string
     */
    private string $originalClassName;

    /**
     * @psalm-var class-string
     */
    private string $mockClassName;

    /**
     * @psalm-var list<string>
     */
    private array $methods;

    private bool $callOriginalConstructor;

    private array $options;

    /**
     * @psalm-param class-string $originalClassName
     * @psalm-param class-string $mockClassName
     */
    public function __construct(Telemetry\Info $telemetryInfo, string $wsdlFile, string $originalClassName, string $mockClassName, array $methods, bool $callOriginalConstructor, array $options)
    {
        $this->telemetryInfo           = $telemetryInfo;
        $this->wsdlFile                = $wsdlFile;
        $this->originalClassName       = $originalClassName;
        $this->mockClassName           = $mockClassName;
        $this->methods                 = $methods;
        $this->callOriginalConstructor = $callOriginalConstructor;
        $this->options                 = $options;
    }

    public function telemetryInfo(): Telemetry\Info
    {
        return $this->telemetryInfo;
    }

    public function wsdlFile(): string
    {
        return $this->wsdlFile;
    }

    /**
     * @psalm-return class-string
     */
    public function originalClassName(): string
    {
        return $this->originalClassName;
    }

    /**
     * @psalm-return class-string
     */
    public function mockClassName(): string
    {
        return $this->mockClassName;
    }

    /**
     * @psalm-return list<string>
     */
    public function methods(): array
    {
        return $this->methods;
    }

    public function callOriginalConstructor(): bool
    {
        return $this->callOriginalConstructor;
    }

    public function options(): array
    {
        return $this->options;
    }

    /**
     * @todo
     */
    public function asString(): string
    {
        return sprintf(
            '%s %s todo',
            $this->telemetryInfo()->asString(),
            self::class
        );
    }
}
