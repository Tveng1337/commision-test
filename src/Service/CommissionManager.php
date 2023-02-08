<?php

namespace App\Service;

use App\Service\BINParcer\BINProviderInterface;
use App\Service\CurrencyRate\CurrencyRatesProviderInterface;
use Exception;

class CommissionManager
{
    /**
     * @var BINProviderInterface
     */
    private BINProviderInterface $binProvider;

    /**
     * @var CurrencyRatesProviderInterface
     */
    private CurrencyRatesProviderInterface $currencyRatesProvider;

    /**
     * @var array
     */
    private array $data;

    public function __construct(BINProviderInterface $binManager, CurrencyRatesProviderInterface $currencyRatesProvider)
    {
        $this->binProvider = $binManager;
        $this->currencyRatesProvider = $currencyRatesProvider;
    }

    /**
     * @return float
     * @throws Exception
     */
    public function proceed(): float
    {
        $this->getData();

        foreach ($this->data as $record) {
            try {
                return $this->getCommission($record);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }

    /**
     * @param $record
     *
     * @return float
     */
    public function getCommission($record): float
    {
        $this->binProvider->request($record->bin);
        $isCardFromEu = $this->binProvider->isCountryEu($this->binProvider->getCountryName());

        $this->currencyRatesProvider->request();
        $rate = $this->currencyRatesProvider->getRate($record->currency);

        $fixedAmount = ($record->currency === 'EUR' || $rate == 0) ? $record->amount : $record->amount / $rate;

        $commission = $fixedAmount * (($isCardFromEu) ? 0.01 : 0.02);

        return round($commission, 3);
    }

    /**
     * @return void
     */
    private function getData()
    {
        $file = file('input.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        foreach ($file as $line) {
            $this->data[] = json_decode($line);
        }
    }
}
