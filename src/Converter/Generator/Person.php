<?php

declare(strict_types=1);

namespace Smile\GdprDump\Converter\Generator;

use Faker\Generator;
use Smile\GdprDump\Converter\ConverterInterface;

class Person implements ConverterInterface
{
    public function convert(mixed $value, array $context = []): string
    {
        $person = new \Faker\Provider\Person(new Generator());
        return $person->firstName() . $person->lastName();
    }

    public function setParameters(array $parameters): void
    {
        //do nothing, no parameters to set
    }
}