<?php
declare(strict_types=1);

namespace Smile\GdprDump\TsvProvider;

abstract class StructuredAddressGzippedTsvReader extends GzippedTsvReader {
    protected array $structuredData;

    protected function init(
        string $postcodeColumnName,
        string $cityColumnName,
        string $streetColumnName,
        string $streetNumberColumnName,
    ): void {
        foreach ($this->data as $row) {
            $this->structuredData
                [$row[$postcodeColumnName]]
                    [$row[$cityColumnName]]
                        [$row[$streetColumnName]][] = $row[$streetNumberColumnName];
        }
    }
}