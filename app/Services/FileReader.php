<?php

namespace App\Services;

class FileReader
{
    public function read(string $filePath): array
    {
        $data = [];

        $fileStream = fopen($filePath, 'r');

        while (!feof($fileStream)) {
            $chunk = fread($fileStream, 8192); // Read 8KB at a time
            $dataChunk = json_decode($chunk, true);
            if ($dataChunk !== null) {
                $data = array_merge($data, $dataChunk);
            }
        }

        fclose($fileStream);

        return $data;
    }
}
