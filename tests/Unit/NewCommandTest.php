<?php

namespace Tests\Unit;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Presta\Services\PrestashopService;

/**
 * 
 */
class NewCommandTest  extends TestCase
{

    /**
     * 
     */
    public function testCreateNewProject()
    {


        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'Hello, World')
        ]);


        $service = new PrestashopService();
        
        $service->createProyect('shipping', 'telepizza', 'Rhonal Chirinos');
        
        $this->assertTrue(true);
    }
}
