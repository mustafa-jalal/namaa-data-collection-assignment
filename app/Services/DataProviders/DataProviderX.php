<?php

namespace App\Services\DataProviders;

use App\Mappers\DataProviderXStatusMapper;
use App\Services\FileReader;
use Illuminate\Support\Collection;

class DataProviderX implements DataProvider
{
    private string $fileName = 'DataProviderX.json';
    private array $data;

    public function __construct(private FileReader $fileReader)
    {
        $filePath = storage_path('app/public/providers/' . $this->fileName);

        $this->data = $this->fileReader->read($filePath);
    }

    public function whereStatusCode(string $status): static
    {
        $this->data = array_filter($this->data, function ($item) use ($status) {
            return $item['statusCode'] == DataProviderXStatusMapper::toCode($status);
        });

        return $this;
    }

    public function whereBalance(int $balance, string $operator): static
    {
        $this->data = array_filter($this->data, function ($item) use ($balance, $operator) {
            return match ($operator) {
                '>=' => $item['parentAmount'] >= $balance,
                '<=' => $item['parentAmount'] <= $balance,
                default => false,
            };
        });

        return $this;
    }

    public function whereCurrency(string $currency): static
    {
        $this->data = array_filter($this->data, function ($item) use ($currency) {
            return $item['Currency'] == $currency;
        });

        return $this;
    }

    public function load(): Collection
    {
        return collect($this->data)->map(function ($item) {
            return [
                'id' => $item['parentIdentification'],
                'email' => $item['parentEmail'],
                'balance' => $item['parentAmount'],
                'currency' => $item['Currency'],
                'status' => DataProviderXStatusMapper::toStatus($item['statusCode']),
                'date' => $item['registerationDate'],
            ];
    });
    }
}
