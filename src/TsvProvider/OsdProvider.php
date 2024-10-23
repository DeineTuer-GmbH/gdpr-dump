<?php
declare(strict_types=1);

namespace Smile\GdprDump\TsvProvider;

class OsdProvider extends StructuredAddressGzippedTsvReader {
    private const TSV_COLUMN_POSTCODE = 'postal_code';
    private const TSV_COLUMN_CITY = 'city';
    private const TSV_COLUMN_STREET = 'street';
    private const TSV_COLUMN_STREETNUMBER = 'house_number';

    /** @var OsdProvider[] $osdCountryProviders  */
    private static array $osdCountryProviders;

    private function __construct(string $countryCode)
    {
        $filename = __DIR__ . DIRECTORY_SEPARATOR . $countryCode . '-houses.tsv.gz';
        parent::__construct($filename);

        $this->init(self::TSV_COLUMN_POSTCODE, self::TSV_COLUMN_CITY, self::TSV_COLUMN_STREET, self::TSV_COLUMN_STREETNUMBER);
    }

    private function __clone() {
        // prevent cloning, should be a singleton
    }

    public static function getInstance(string $countryCode): self
    {
        return
            self::$osdCountryProviders[$countryCode] ??
            (self::$osdCountryProviders[$countryCode] = new self($countryCode));
    }

    public function getPostcode(): string
    {
        $count = 0;
        $tries = 0;
        $randomElement = '';
        while ($count === 0 && $tries < 5) {
            /** @noinspection RandomApiMigrationInspection not working with crypto here */
            $randomElement = array_slice(
                $this->structuredData,
                rand(0, count($this->structuredData)),
                1,
                true
            );
            $count = count($randomElement);
            $tries++;
        }

        return (string) array_key_first($randomElement);
    }

    public function getCity(string $postcode): string
    {
        $count = 0;
        $tries = 0;
        $randomElement = '';
        while ($count === 0 && $tries < 5) {
            /** @noinspection RandomApiMigrationInspection not working with crypto here */
            $randomElement = array_slice(
                $this->structuredData[$postcode],
                rand(0, count($this->structuredData[$postcode])),
                1,
                true
            );
            $count = count($randomElement);
            $tries++;
        }

        return (string) array_key_first($randomElement);
    }

    public function getStreet(string $postcode, string $city): string
    {
        $count = 0;
        $tries = 0;
        $randomElement = '';
        while ($count === 0 && $tries < 5) {
            /** @noinspection RandomApiMigrationInspection not working with crypto here */
            $randomElement = array_slice(
                $this->structuredData[$postcode][$city],
                rand(0, count($this->structuredData[$postcode][$city])),
                1,
                true
            );
            $count = count($randomElement);
            $tries++;
        }

        return (string) array_key_first($randomElement);
    }

    public function getStreetNumber(string $postcode, string $city, string $street): string
    {
        $count = 0;
        $tries = 0;
        $randomElement = '';
        while ($count === 0 && $tries < 5) {
            /** @noinspection RandomApiMigrationInspection not working with crypto here */
            $randomElement = array_slice(
                $this->structuredData[$postcode][$city][$street],
                rand(0, count($this->structuredData[$postcode][$city][$street])),
                1,
                true
            );
            $count = count($randomElement);
            $tries++;
        }

        return (string) array_key_first($randomElement);
    }
}