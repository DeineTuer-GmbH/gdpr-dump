<?php
declare(strict_types=1);

namespace Smile\GdprDump\TsvProvider;

abstract class GzippedTsvReader {
    protected array $data;
    protected array $headers;

    public function __construct(string $filename) {
        if (!file_exists($filename)) {
            throw new \Exception("Could not find file: $filename");
        }

        $file = gzopen($filename, 'rb');
        if ($file) {
            $this->headers = fgetcsv($file, 0, "\t");
            while (($row = fgetcsv($file, 0, "\t")) !== false) {
                $this->data[] = array_combine($this->headers, $row);
            }
            gzclose($file);
        } else {
            throw new \Exception("Could not open file: $filename");
        }
    }
}