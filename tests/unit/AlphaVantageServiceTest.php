<?php
// tests/Service/AlphaVantageServiceTest.php

namespace App\Tests\Service;

use App\Service\AlphaVantageService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Psr\Log\NullLogger;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class AlphaVantageServiceTest extends TestCase
{
    public function testGetStockQuoteReturnsData()
    {
        $cache = new ArrayAdapter();
        $logger = new NullLogger();
        $apiKey = 'test_api_key';

        // Create a mock HTTP client
        $httpClient = $this->createMock(Client::class);

        // Create a mock response
        $responseBody = json_encode([
            'Global Quote' => [
                '01. symbol' => 'AAPL',
                '05. price' => '150.00',
            ],
        ]);

        // Configure the mock client to return the mock response
        $httpClient->method('request')
            ->willReturn(new Response(200, [], $responseBody));

        $service = new AlphaVantageService($httpClient, $cache, $logger, $apiKey);

        $result = $service->getStockQuote('AAPL');

        $this->assertIsString($result);
        $this->assertEquals('150.00', $result);
    }

    public function testGetStockQuoteUsesCache()
    {
        $cache = new ArrayAdapter();
        $logger = new NullLogger();
        $apiKey = 'test_api_key';

        // Set a cache item
        $cacheItem = $cache->getItem('stock_quote_AAPL');
        $cacheItem->set('150.00');
        $cache->save($cacheItem);

        // Create a mock HTTP client
        $httpClient = $this->createMock(Client::class);

        $service = new AlphaVantageService($httpClient, $cache, $logger, $apiKey);

        $result = $service->getStockQuote('AAPL');

        $this->assertIsString($result);
        $this->assertEquals('150.00', $result);
    }

    // Add more tests as needed
}
