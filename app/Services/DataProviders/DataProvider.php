<?php

namespace App\Services\DataProviders;


use Illuminate\Support\Collection;

interface DataProvider
{
    public function whereStatusCode(string $status): self;
    public function whereBalance(int $balance, string $operator): self;
    public function whereCurrency(string $currency): self;
    public function load(): Collection;
}
