<?php

declare(strict_types=1);

namespace Smile\GdprDump\Converter\Generator;

use Smile\GdprDump\Converter\ConverterInterface;
use Smile\GdprDump\Converter\Proxy\Faker;
use Smile\GdprDump\Faker\FakerService;

class Person implements ConverterInterface
{
    public function convert(mixed $value, array $context = []): string
    {
        $converter = new Faker(new FakerService());
        $converter->setParameters(['firstName']);
        $firstName = $converter->convert($value, $context);

        $converter = new Faker(new FakerService());
        $converter->setParameters(['firstName']);
        $lastName = $converter->convert($value, $context);

        return 'test tester'; //$firstName . ' ' . $lastName;
    }

    public function setParameters(array $parameters): void
    {
        //do nothing, no parameters to set
    }
}