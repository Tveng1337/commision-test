<?php

namespace App\Tests;

use App\Service\CurrencyRate\Adapter\ExchangerManager;
use PHPUnit\Framework\TestCase;
use Mockery;

class ExchangerManagerTest extends TestCase
{
    public function testRequest()
    {
        $service = Mockery::mock(ExchangerManager::class);
        $service->shouldReceive('request')->once()->andReturn(null);

        $this->assertNull($service->request());
    }

    public function testGetRate()
    {
        $service = Mockery::mock(ExchangerManager::class);
        $service->shouldReceive('getRate')->with('FR')->once()->andReturn(3.5555);

        $this->assertIsFloat($service->getRate('FR'));
    }
}
