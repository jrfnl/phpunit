<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Event;

/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */
final class Facade
{
    private static ?TypeMap $typeMap = null;

    private static ?Emitter $emitter = null;

    private static ?DeferredDispatcher $dispatcher = null;

    private static bool $sealed = false;

    /**
     * @internal This method is not covered by the backward compatibility promise for PHPUnit
     */
    public static function emitter(): Emitter
    {
        if (self::$emitter === null) {
            self::$emitter = new DispatchingEmitter(
                self::dispatcher(),
                new Telemetry\System(
                    new Telemetry\SystemStopWatch(),
                    new Telemetry\SystemMemoryMeter()
                )
            );
        }

        return self::$emitter;
    }

    /**
     * @throws EventFacadeIsSealedException
     */
    public static function registerTracer(Tracer\Tracer $tracer): void
    {
        if (self::$sealed) {
            throw new EventFacadeIsSealedException;
        }

        self::dispatcher()->registerTracer($tracer);
    }

    /**
     * @throws EventFacadeIsSealedException
     * @throws UnknownSubscriberTypeException
     */
    public static function registerSubscriber(Subscriber $subscriber): void
    {
        if (self::$sealed) {
            throw new EventFacadeIsSealedException;
        }

        self::dispatcher()->registerSubscriber($subscriber);
    }

    public static function seal(): void
    {
        self::$dispatcher->flush();

        self::$sealed = true;

        self::emitter()->eventFacadeSealed();
    }

    private static function dispatcher(): DeferredDispatcher
    {
        if (self::$dispatcher === null) {
            self::$dispatcher = new DeferredDispatcher(
                new DirectDispatcher(self::typeMap())
            );
        }

        return self::$dispatcher;
    }

    private static function typeMap(): TypeMap
    {
        if (self::$typeMap === null) {
            $typeMap = new TypeMap;

            self::registerDefaultTypes($typeMap);

            self::$typeMap = $typeMap;
        }

        return self::$typeMap;
    }

    private static function registerDefaultTypes(TypeMap $typeMap): void
    {
        $defaultEvents = [
            Assertion\Made::class,
            Bootstrap\Finished::class,
            Comparator\Registered::class,
            Extension\Loaded::class,
            GlobalState\Captured::class,
            GlobalState\Modified::class,
            GlobalState\Restored::class,
            Test\Aborted::class,
            Test\AfterLastTestMethodCalled::class,
            Test\AfterLastTestMethodFinished::class,
            Test\AfterTestMethodCalled::class,
            Test\AfterTestMethodFinished::class,
            Test\BeforeFirstTestMethodCalled::class,
            Test\BeforeFirstTestMethodFinished::class,
            Test\BeforeTestMethodCalled::class,
            Test\BeforeTestMethodFinished::class,
            Test\Errored::class,
            Test\Failed::class,
            Test\Finished::class,
            Test\OutputPrinted::class,
            Test\Passed::class,
            Test\PassedButRisky::class,
            Test\PassedWithWarning::class,
            Test\PostConditionCalled::class,
            Test\PostConditionFinished::class,
            Test\PreConditionCalled::class,
            Test\PreConditionFinished::class,
            Test\Prepared::class,
            Test\SkippedByDataProvider::class,
            Test\SkippedDueToUnsatisfiedRequirements::class,
            Test\Skipped::class,
            TestDouble\MockObjectCreated::class,
            TestDouble\MockObjectCreatedForAbstractClass::class,
            TestDouble\MockObjectCreatedForTrait::class,
            TestDouble\MockObjectCreatedFromWsdl::class,
            TestDouble\PartialMockObjectCreated::class,
            TestDouble\TestProxyCreated::class,
            TestDouble\TestStubCreated::class,
            TestRunner\EventFacadeSealed::class,
            TestRunner\Finished::class,
            TestRunner\Started::class,
            TestSuite\Finished::class,
            TestSuite\Loaded::class,
            TestSuite\Sorted::class,
            TestSuite\Started::class,
        ];

        foreach ($defaultEvents as $eventClass) {
            $typeMap->addMapping(
                $eventClass . 'Subscriber',
                $eventClass
            );
        }
    }
}
