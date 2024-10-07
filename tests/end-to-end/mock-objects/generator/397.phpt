--TEST--
https://github.com/sebastianbergmann/phpunit-mock-objects/issues/397
--FILE--
<?php declare(strict_types=1);
class C
{
    public function m(?self $other): self
    {
    }
}

require_once __DIR__ . '/../../../bootstrap.php';

$generator = new \PHPUnit\Framework\MockObject\Generator\Generator;

$mock = $generator->generate(
    type: C::class,
    mockObject: true,
    markAsMockObject: true,
    methods: [],
    mockClassName: 'MockC',
);

print $mock->classCode();
--EXPECTF--
declare(strict_types=1);

class MockC extends C implements PHPUnit\Framework\MockObject\MockObjectInternal
{
    use PHPUnit\Framework\MockObject\%SStubApi;
    use PHPUnit\Framework\MockObject\MockObjectApi;
    use PHPUnit\Framework\MockObject\GeneratedAsMockObject;
    use PHPUnit\Framework\MockObject\Method;
    use PHPUnit\Framework\MockObject\DoubledCloneMethod;

    public function m(?C $other): C
    {
        $__phpunit_definedVariables        = get_defined_vars();
        $__phpunit_namedVariadicParameters = [];

        foreach ($__phpunit_definedVariables as $__phpunit_definedVariableName => $__phpunit_definedVariableValue) {
            if ((new ReflectionParameter([__CLASS__, __FUNCTION__], $__phpunit_definedVariableName))->isVariadic()) {
                foreach ($__phpunit_definedVariableValue as $__phpunit_key => $__phpunit_namedValue) {
                    if (is_string($__phpunit_key)) {
                        $__phpunit_namedVariadicParameters[$__phpunit_key] = $__phpunit_namedValue;
                    }
                }
            }
        }

        $__phpunit_arguments = [$other];
        $__phpunit_count     = func_num_args();

        if (1 !== null && $__phpunit_count > 1) {
            $__phpunit_arguments_tmp = func_get_args();

            for ($__phpunit_i = 1; $__phpunit_i < $__phpunit_count; $__phpunit_i++) {
                $__phpunit_arguments[] = $__phpunit_arguments_tmp[$__phpunit_i];
            }
        }

        $__phpunit_arguments = array_merge($__phpunit_arguments, $__phpunit_namedVariadicParameters);

        $__phpunit_result = $this->__phpunit_getInvocationHandler()->invoke(
            new \PHPUnit\Framework\MockObject\Invocation(
                'C', 'm', $__phpunit_arguments, 'C', $this, true
            )
        );

        return $__phpunit_result;
    }
}
