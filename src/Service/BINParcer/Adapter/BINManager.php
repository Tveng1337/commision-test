<?php

namespace App\Service\BINParcer\Adapter;

use App\Service\BINParcer\BINProviderInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\Trait\CountryTrait;
use Exception;

class BINManager implements BINProviderInterface
{
    use CountryTrait;

    /**
     * @var HttpClientInterface|null
     */
    private ?HttpClientInterface $apiService = null;

    /**
     * @var array
     */
    private array $binResponse;

    public function __construct(HttpClientInterface $apiClient)
    {
        $this->apiService = $apiClient;
    }

    /**
     * @param string $cardNumber
     *
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function request(string $cardNumber): void
    {
        $this->binResponse = $this->apiService->request(
            'GET',
            "https://lookup.binlist.net/" . $cardNumber
        )->toArray();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getCountryName(): string
    {
        try {
            return $this->binResponse['country']['alpha2'];
        } catch (Exception $e) {
            throw new Exception('Country not found');
        }
    }
}
