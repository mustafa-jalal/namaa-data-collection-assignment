<?php

namespace App\Services\DataProviders;

use App\Mappers\DataProviderXStatusMapper;
use App\Mappers\DataProviderYStatusMapper;
use App\Services\FileReader;
use Illuminate\Support\Collection;

class DataProviderY implements DataProvider
{
    private string $fileName = 'DataProviderY.json';
    private array $data;

    public function __construct(private FileReader $fileReader)
    {
        $filePath = storage_path('app/public/providers/' . $this->fileName);

        $this->data = $this->fileReader->read($filePath);
    }

    public function whereStatusCode(string $status): static
    {
        $this->data = array_filter($this->data, function ($item) use ($status) {
            return $item['status'] == DataProviderYStatusMapper::toCode($status);
        });

        return $this;
    }

    public function whereBalance(int $balance, string $operator): static
    {
        $this->data = array_filter($this->data, function ($item) use ($balance, $operator) {
            return match ($operator) {
                '>=' => $item['balance'] >= $balance,
                '<=' => $item['balance'] <= $balance,
                default => false,
            };
        });

        return $this;
    }

    public function whereCurrency(string $currency): static
    {
        $this->data = array_filter($this->data, function ($item) use ($currency) {
            return $item['currency'] == $currency;
        });

        return $this;
    }

    public function load(): Collection
    {
        return collect($this->data)->map(function ($item) {
            return [
                'id' => $item['id'],
                'email' => $item['email'],
                'balance' => $item['balance'],
                'currency' => $item['currency'],
                'status' => DataProviderYStatusMapper::toStatus($item['status']),
                'date' => $item['created_at'],
            ];
        });
    }
}
