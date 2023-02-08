<?php

namespace App\Tests;

use App\Service\BINParcer\Adapter\BINManager;
use App\Service\CommissionManager;
use App\Service\CurrencyRate\Adapter\ExchangerManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CommissionManagerTest extends TestCase
{
    const MOCK_DATA = [
        [
            'alpha2'   => "FR",
            'base'     => "EUR",
            'rates'    => 1,
            'expected' => 1,
        ],
        [
            'alpha2'   => "LT",
            'base'     => "USD",
            'rates'    => 1,
            'expected' => 0.5,
        ],
        [
            'alpha2'   => "JP",
            'base'     => "JPY",
            'rates'    => 1,
            'expected' => 200,
        ],
        [
            'alpha2'   => "US",
            'base'     => "USD",
            'rates'    => 1,
            'expected' => 2.6,
        ],
        [
            'alpha2'   => "UK",
            'base'     => "GBP",
            'rates'    => 1,
            'expected' => 40,
        ],
    ];

    const RECORDS = '[{"bin":"45717360","amount":"100.00","currency":"EUR"},
        {"bin":"516793","amount":"50.00","currency":"USD"},
        {"bin":"45417360","amount":"10000.00","currency":"JPY"},
        {"bin":"41417360","amount":"130.00","currency":"USD"},
        {"bin":"4745030","amount":"2000.00","currency":"GBP"}]';

    public static function provideCardsData(): iterable
    {
        $input = json_decode(self::RECORDS);
        foreach (self::MOCK_DATA as $key => $record) {
            yield "card_{$key}" => [
                $record['expected'],
                new MockResponse(json_encode(["country" => ["alpha2" => $record['alpha2']]]), ['http_code' => 200]),
                new MockResponse(
                    json_encode([
                        "base"  => $record['base'],
                        "rates" => [$record['base'] => $record['rates']],
                    ]), ['http_code' => 200]
                ),
                $input[$key],
            ];
        }
    }

    /**
     * @dataProvider provideCardsData
     */
    public function testProceed(
        float $expected,
        ResponseInterface $responseBIN,
        ResponseInterface $responseExchange,
        $record
    ) {
        $client = new MockHttpClient([$responseBIN, $responseExchange]);

        $binService = new BINManager($client);
        $exService = new ExchangerManager($client);

        $commissionService = new CommissionManager($binService, $exService);
        $commission = $commissionService->getCommission($record);

        $this->assertEquals($expected, $commission);
    }
}
