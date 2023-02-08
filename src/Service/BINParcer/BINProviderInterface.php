<?php

namespace App\Service\BINParcer;

interface BINProviderInterface
{
    /**
     * @param string $cardNumber
     *
     * @return void
     */
    public function request(string $cardNumber): void;

    /**
     * @return string
     */
    public function getCountryName(): string;
}
