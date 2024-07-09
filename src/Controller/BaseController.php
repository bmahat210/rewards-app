<?php
// src/Controller/BaseController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AlphaVantageService;

class BaseController extends AbstractController
{
    private $alphaVantageService;

    public function __construct(AlphaVantageService $alphaVantageService)
    {
        $this->alphaVantageService = $alphaVantageService;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        $ticker = 'AAPL'; // Example ticker symbol
        $stockData = $this->alphaVantageService->getStockQuote($ticker);

        return $this->render('base.html.twig', [
            'stockData' => $stockData,
        ]);
    }
}
