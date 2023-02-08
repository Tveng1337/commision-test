<?php

namespace App\Service\CurrencyRate;

interface CurrencyRatesProviderInterface
{
    /**
     * @return void
     */
    public function request(): void;

    /**
     * @param string $currency
     *
     * @return float
     */
    public function getRate(string $currency): float;
}
