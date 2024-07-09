<?php

// src/EventListener/StockDataListener.php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use App\Service\AlphaVantageService;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class StockDataListener
{
    private $stockDataService;
    private $logger;

    public function __construct(AlphaVantageService $stockDataService, LoggerInterface $logger)
    {
        $this->stockDataService = $stockDataService;
        $this->logger = $logger;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        // Check if the controller is a Symfony controller
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        // Fetch stock data
        $symbol = 'EAT';
        $stockData = $this->stockDataService->getStockQuote($symbol);
        $this->logger->error('An erroasdlkfjasldjfklajsdlkfjaslkdjfklasjdaf');
        // Store stock data in request attributes
        $event->getRequest()->attributes->set('_stock_data', $stockData);
    }
}
