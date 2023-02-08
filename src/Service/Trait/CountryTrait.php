<?php

namespace App\Service\Trait;

trait CountryTrait
{
    /**
     * @var array|string[]
     */
    private array $euCountriesList = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    /**
     * @param string $countryCode
     *
     * @return bool
     */
    public function isCountryEu(string $countryCode): bool
    {
        return in_array($countryCode, $this->euCountriesList, true);
    }
}
