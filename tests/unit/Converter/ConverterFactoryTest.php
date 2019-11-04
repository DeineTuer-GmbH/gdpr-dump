<?php
declare(strict_types=1);

namespace Smile\GdprDump\Tests\Unit\Converter;

use Smile\GdprDump\Converter\ConverterFactory;
use Smile\GdprDump\Converter\Dummy;
use Smile\GdprDump\Converter\Faker;
use Smile\GdprDump\Converter\Proxy\Cache;
use Smile\GdprDump\Converter\Proxy\Chain;
use Smile\GdprDump\Converter\Proxy\Conditional;
use Smile\GdprDump\Converter\Proxy\Unique;
use Smile\GdprDump\Faker\FakerService;
use Smile\GdprDump\Tests\Framework\Mock\Converter\ConverterMock;
use Smile\GdprDump\Tests\Unit\TestCase;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class ConverterFactoryTest extends TestCase
{
    /**
     * Test the converter creation from an array definition.
     */
    public function testConverterCreation()
    {
        $factory = $this->createFactory();

        $converter = $factory->create(['converter' => ConverterMock::class, 'parameters' => ['prefix' => '']]);
        $this->assertInstanceOf(ConverterMock::class, $converter);
    }

    /**
     * Test the creation of a Faker converter.
     */
    public function testFakerConverter()
    {
        $factory = $this->createFactory();

        $converter = $factory->create(['converter' => 'faker', 'parameters' => ['formatter' => 'safeEmail']]);
        $this->assertInstanceOf(Faker::class, $converter);
    }

    /**
     * Test the creation of a disabled converter.
     */
    public function testDisabledConverter()
    {
        $factory = $this->createFactory();

        $converter = $factory->create(['converter' => 'faker', 'disabled' => true]);
        $this->assertInstanceOf(Dummy::class, $converter);
    }

    /**
     * Test the creation of a unique converter.
     */
    public function testUniqueConverter()
    {
        $factory = $this->createFactory();

        $converter = $factory->create(['converter' => ConverterMock::class, 'unique' => true]);
        $this->assertInstanceOf(Unique::class, $converter);

        $converter = $factory->create(['converter' => ConverterMock::class, 'unique' => false]);
        $this->assertInstanceOf(ConverterMock::class, $converter);
    }

    /**
     * Test the creation of a conditional converter.
     */
    public function testConditionConverter()
    {
        $factory = $this->createFactory();

        $converter = $factory->create(['converter' => ConverterMock::class, 'condition' => '{{id}} === 1']);
        $this->assertInstanceOf(Conditional::class, $converter);
    }

    /**
     * Test the creation of a cache converter.
     */
    public function testCacheConverter()
    {
        $factory = $this->createFactory();

        $converter = $factory->create(['converter' => ConverterMock::class, 'cache_key' => 'test']);
        $this->assertInstanceOf(Cache::class, $converter);
    }

    /**
     * Test the creation of nested converters.
     */
    public function testNestedConverters()
    {
        $factory = $this->createFactory();

        $converter = $factory->create([
            'converter' => Chain::class,
            'parameters' => [
                'converters' => [
                    ['converter' => ConverterMock::class],
                    ['converter' => ConverterMock::class],
                ],
            ]
        ]);
        $this->assertInstanceOf(Chain::class, $converter);
    }

    /**
     * Assert that an exception is thrown when the converter is set but empty.
     *
     * @expectedException \UnexpectedValueException
     */
    public function testEmptyConverter()
    {
        $factory = $this->createFactory();
        $factory->create(['converter' => null]);
    }

    /**
     * Assert that an exception is thrown when the converter is not set.
     *
     * @expectedException \UnexpectedValueException
     */
    public function testConverterNotSet()
    {
        $factory = $this->createFactory();
        $factory->create([]);
    }

    /**
     * Assert that an exception is thrown when the converter is not defined.
     *
     * @expectedException \RuntimeException
     */
    public function testConverterNotDefined()
    {
        $factory = $this->createFactory();
        $factory->create(['converter' => 'notExists']);
    }

    /**
     * Assert that an exception is thrown when the "parameters" parameter is not an array.
     *
     * @expectedException \UnexpectedValueException
     */
    public function testParametersNotAnArray()
    {
        $factory = $this->createFactory();
        $factory->create(['converter' => ConverterMock::class, 'parameters' => '']);
    }

    /**
     * Assert that an exception is thrown when a "converter" parameter is used,
     * but the value is not a converter definition.
     *
     * @expectedException \UnexpectedValueException
     */
    public function testConverterParameterMalformed()
    {
        $factory = $this->createFactory();
        $factory->create(['converter' => ConverterMock::class, 'parameters' => ['converter' => null]]);
    }

    /**
     * Assert that an exception is thrown when a "converters" parameter is used,
     * but the value is not an array.
     *
     * @expectedException \UnexpectedValueException
     */
    public function testConvertersParameterNotAnArray()
    {
        $factory = $this->createFactory();
        $factory->create(['converter' => ConverterMock::class, 'parameters' => ['converters' => null]]);
    }

    /**
     * Assert that an exception is thrown when a "converters" parameter is used,
     * but the value is not an array of converter definition.
     *
     * @expectedException \UnexpectedValueException
     */
    public function testConvertersParameterMalformed()
    {
        $factory = $this->createFactory();
        $factory->create(['converter' => ConverterMock::class, 'parameters' => ['converters' => [null]]]);
    }

    /**
     * Create a converter factory object.
     *
     * @return ConverterFactory
     */
    private function createFactory(): ConverterFactory
    {
        return new ConverterFactory(new FakerService());
    }
}
