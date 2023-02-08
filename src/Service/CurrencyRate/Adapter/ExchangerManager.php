<?php

namespace App\Service\CurrencyRate\Adapter;

use App\Service\CurrencyRate\CurrencyRatesProviderInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Exception;

class ExchangerManager implements CurrencyRatesProviderInterface
{
    /**
     * @var HttpClientInterface|null
     */
    private ?HttpClientInterface $apiService = null;

    /**
     * @var array
     */
    private array $exchangeResponse;

    public function __construct(HttpClientInterface $apiClient)
    {
        $this->apiService = $apiClient;
    }

    /**
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function request(): void
    {
        $this->exchangeResponse = $this->apiService->request('GET',
            "https://api.apilayer.com/exchangerates_data/latest",
            ['apikey' => 'MZTJlXQJRKbZS8ATSVoM82pzNqWLT7ua'])
            ->toArray();
    }

    /**
     * @param string $currency
     *
     * @return float
     * @throws Exception
     */
    public function getRate(string $currency): float
    {
        try {
            return $this->exchangeResponse['rates'][$currency];
        } catch (Exception $e) {
            throw new Exception('Can not be found.');
        }
    }
}
