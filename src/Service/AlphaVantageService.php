<?php
// src/Service/AlphaVantageService.php

namespace App\Service;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class AlphaVantageService
{
    private $httpClient;
    private $apiKey;
    private $cache;
    private $logger;

    public function __construct(Client $httpClient = null, AdapterInterface $cache, LoggerInterface $logger, string $apiKey)
    {
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => 'https://www.alphavantage.co/',
            'timeout'  => 10.0,
        ]);
        $this->apiKey = $apiKey;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function getStockQuote($symbol)
    {
        $cacheKey = 'stock_quote_' . $symbol;

        // Check if the data is already cached
        $cachedValue = $this->cache->getItem($cacheKey);

        if ($cachedValue->isHit()) {
            $this->logger->error(json_encode($cachedValue->get()));
            if($cachedValue->get())
                return $cachedValue->get();
        }

        try {
            $response = $this->httpClient->request('GET', 'query', [
                'query' => [
                    'function' => 'GLOBAL_QUOTE',
                    'symbol' => $symbol,
                    'apikey' => $this->apiKey,
                ],
            ]);
            $this->logger->error($response->getBody());
            $stockData = json_decode($response->getBody(), true)['Global Quote'] ?? [];
            $this->logger->error(json_encode($stockData));

            if (count($stockData) > 0) {
                $price = $stockData['05. price'];
                $this->logger->error($price);
                $this->logger->error('$price');

                // Cache the fetched data for 1 hour (adjust TTL as needed)
                $cachedValue->set($price);
                $cachedValue->expiresAfter(60 * 60); // 1 hour
                $this->cache->save($cachedValue);
                return $price;
            }
            $this->logger->error('Error fetching stock data from the api: ' . json_encode($stockData));

        } catch (\Exception $e) {
            // Handle exception (e.g., log or throw custom exception)
            $this->logger->error('Error fetching stock quote: ' . $e->getMessage());
            return [];
        }
    }
}
