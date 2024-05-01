<?php

namespace App\Services;

use App\Services\DataProviders\DataProviderX;
use App\Services\DataProviders\DataProviderY;
use Illuminate\Support\Collection;

class UserTransactionsService
{
    private array $providers;

    public function __construct()
    {
        $this->providers = [
            'DataProviderX' => DataProviderX::class,
            'DataProviderY' => DataProviderY::class,
        ];
    }

    public function getAllTransactions(array $filters): Collection
    {
        $filteredProviders = $this->filterProviders($filters);

        $transactions = collect();

        foreach ($filteredProviders as $provider) {
            $transactions = $transactions->merge(
                $this->getTransactionsFromProvider($provider, $filters)
            );
        }

        return $transactions;
    }

    public function filterProviders(array $filters): array
    {
        if (isset($filters['provider'])) {
            if (array_key_exists($filters['provider'], $this->providers)) {
                return [$this->providers[$filters['provider']]];
            }
            throw new \InvalidArgumentException("Invalid provider");
        }

        return $this->providers;
    }

    public function getTransactionsFromProvider(string $providerClass, array $filters): Collection
    {
        $provider = new $providerClass(new FileReader());

        if (isset($filters['statusCode'])) {
            $provider->whereStatusCode($filters['statusCode']);
        }

        if (isset($filters['balanceMin'])) {
            $provider->whereBalance($filters['balanceMin'], '>=');
        }

        if (isset($filters['balanceMax'])) {
            $provider->whereBalance($filters['balanceMax'], '<=');
        }

        if (isset($filters['currency'])) {
            $provider->whereCurrency($filters['currency']);
        }

        return $provider->load();
    }
}
