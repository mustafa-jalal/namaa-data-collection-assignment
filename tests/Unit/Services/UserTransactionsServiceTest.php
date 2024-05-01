<?php

namespace Tests\Unit\Services;

use App\Services\UserTransactionsService;
use App\Services\DataProviders\DataProviderX;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UserTransactionsServiceTest extends TestCase
{
    private UserTransactionsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserTransactionsService();
    }

    public function testGetAllTransactionsReturnsCollection()
    {
        $transactions = $this->service->getAllTransactions([]);
        $this->assertInstanceOf(Collection::class, $transactions);
    }

    public function testGetAllTransactionsWithNoFilters()
    {
        $transactions = $this->service->getAllTransactions([]);
        $this->assertNotEmpty($transactions);
    }

    public function testGetAllTransactionsWithProviderFilter()
    {
        $transactions = $this->service->getAllTransactions(['provider' => 'DataProviderX']);
        $this->assertNotEmpty($transactions);
    }

    public function testGetAllTransactionsWithInvalidProviderFilter()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid provider');

        $this->service->getAllTransactions(['provider' => 'InvalidProvider']);
    }

    public function testFilterProvidersWithNoFilters()
    {
        $providers = $this->service->filterProviders([]);
        $this->assertCount(2, $providers);
    }

    public function testFilterProvidersWithValidProviderFilter()
    {
        $providers = $this->service->filterProviders(['provider' => 'DataProviderX']);
        $this->assertCount(1, $providers);
    }

    public function testFilterProvidersWithInvalidProviderFilter()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid provider');

        // Act
        $this->service->filterProviders(['provider' => 'InvalidProvider']);
    }

    public function testGetTransactionsFromProviderReturnsCollection()
    {
        $transactions = $this->service->getTransactionsFromProvider(DataProviderX::class, []);
        $this->assertInstanceOf(Collection::class, $transactions);
    }

    public function testGetAllTransactionsWithBalanceFilter()
    {
        $transactions = $this->service->getAllTransactions(['balanceMin' => 100, 'balanceMax' => 500]);
        $this->assertNotEmpty($transactions);
        foreach ($transactions as $transaction) {
            $this->assertGreaterThanOrEqual(100, $transaction['balance']);
            $this->assertLessThanOrEqual(500, $transaction['balance']);
        }
    }

    public function testGetAllTransactionsWithStatusCodeFilter()
    {
        $transactions = $this->service->getAllTransactions(['statusCode' => 'authorised']);
        $this->assertNotEmpty($transactions);
        foreach ($transactions as $transaction) {
            $this->assertEquals('authorised', $transaction['status']);
        }
    }

    public function testGetAllTransactionsWithCurrencyFilter()
    {
        $transactions = $this->service->getAllTransactions(['currency' => 'USD']);
        $this->assertNotEmpty($transactions);
        foreach ($transactions as $transaction) {
            $this->assertEquals('USD', $transaction['currency']);
        }
    }

}
