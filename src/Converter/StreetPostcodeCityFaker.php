<?php
declare(strict_types=1);

namespace Smile\GdprDump\Converter\Proxy;

use Smile\GdprDump\Converter\ConverterInterface;
use Smile\GdprDump\Converter\Parameters\Parameter;
use Smile\GdprDump\Converter\Parameters\ParameterProcessor;
use Smile\GdprDump\TsvProvider\OsdProvider;
use Smile\GdprDump\Util\ArrayHelper;

class StreetPostcodeCityFaker implements ConverterInterface
{
    private bool $isEav;
    private string $country;
    private string $postcode;
    private string $city;
    private string $street;

    public function setParameters(array $parameters): void
    {
        $input = (new ParameterProcessor())
            ->addParameter('eav', Parameter::TYPE_BOOL, true)
            ->addParameter('country', Parameter::TYPE_STRING, true)
            ->addParameter('postcode', Parameter::TYPE_STRING, true)
            ->addParameter('street', Parameter::TYPE_STRING, true)
            ->addParameter('city', Parameter::TYPE_STRING, true)
            ->process($parameters);

        $this->country = $input->get('country');
        $this->postcode = $input->get('postcode');
        $this->city = $input->get('city');
        $this->street = $input->get('street');
    }

    public function convert(mixed $value, array $context = []): string
    {
        $currentField = array_search($value, $context['row_data'], true);
        $provider = OsdProvider::getInstance($context['row_data'][$this->country]);
        //TODO: distinguish between isEav and not

        return match ($currentField) {
            $this->postcode => $provider->getPostcode(),
            $this->city => $provider
                ->getCity($context['processed_data'][$this->postcode] ?? $context['row_data'][$this->postcode]),
            $this->street => $provider
                ->getStreet($context['processed_data'][$this->postcode] ?? $context['row_data'][$this->postcode],
                    $context['processed_data'][$this->city] ?? $context['row_data'][$this->city]),
            default => ''
        };
    }
}
