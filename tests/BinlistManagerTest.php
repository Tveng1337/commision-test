<?php

namespace App\Tests;

use App\Service\BINParcer\Adapter\BINManager;
use Mockery;
use PHPUnit\Framework\TestCase;

class BinlistManagerTest extends TestCase
{
    public function testRequest()
    {
        $service = Mockery::mock(BINManager::class);
        $service->shouldReceive('request')->with(3333)->once()->andReturn(null);

        $this->assertNull($service->request(3333));
    }

    public function testGetCountryCode()
    {
        $service = Mockery::mock(BINManager::class);
        $service->shouldReceive('getCountryName')->once()->andReturn('');

        $this->assertEquals('', $service->getCountryName());
    }
}
