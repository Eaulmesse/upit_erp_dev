<?php

namespace App\Service;

use App\Entity\QuotationLines;
use App\Repository\ProductsRepository;
use Psr\Log\LoggerInterface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\QuotationLinesRepository;
use App\Repository\QuotationsRepository;

class QuotationLinesApiService // Remplacement de ExpenseLinesApiService par QuotationLinesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function getData(SessionInterface $session, EntityManagerInterface $em, $quotationsData, QuotationLinesRepository $quotationLinesRepository, QuotationsRepository $quotationsRepository, ProductsRepository $productsRepository): Response
    {
        $session->set('quotation_data', $quotationsData);

        $this->dataCheck($session, $em, $quotationLinesRepository, $quotationsRepository, $productsRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, QuotationLinesRepository $quotationLinesRepository, QuotationsRepository $quotationsRepository, ProductsRepository $productsRepository): Response
    {
        $quotationData = $session->get("quotation_data");

        $this->quotationLinesToDatabase($quotationData, $em, $quotationsRepository, $productsRepository);

        $this->saveQuotationLines($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function quotationLinesToDatabase($quotationData, EntityManagerInterface $em, QuotationsRepository $quotationsRepository, ProductsRepository $productsRepository, ?QuotationLines $quotationLines = null): void
    {
        $quotationLinesData = $quotationData["quotation_lines"];
        foreach ($quotationLinesData as $lines) {
            $quotationLines = new QuotationLines(); 

            $quotationLines->setQuotationsId($quotationsRepository->find($quotationData['id']));
        
            $quotationLines->setProducts($productsRepository->find($lines["product_id"]));
            $quotationLines->setProductInternalId(intval($lines['product_internal_id']));
            $quotationLines->setProductName($lines['product_name']);
            $quotationLines->setProductCode($lines['product_code']);
            $quotationLines->setTitle($lines['title']);
            $quotationLines->setDetails($lines['details']);
            $quotationLines->setQuantity($lines['quantity']);
            $quotationLines->setUnit($lines['unit']);
            $quotationLines->setPrice($lines['price']);
            $quotationLines->setTaxRates($lines['tax_rates'][0]['rate']);
            $quotationLines->setTaxName($lines['tax_rates'][0]['name']);
            $quotationLines->setLineDiscountAmount($lines['line_discount_amount']);
            $quotationLines->setLineDiscountAmountWithTax($lines['line_discount_amount_with_tax']);
            $quotationLines->setLineDiscountUnitIsPercent($lines['line_discount_unit_is_percent']);
            $quotationLines->setTaxAmount($lines['tax_amount']);
            $quotationLines->setPreTaxAmount($lines['pre_tax_amount']);
            $quotationLines->setTotalAmount($lines['total_amount']);
            $quotationLines->setMargin($lines['margin']);
            $quotationLines->setUnitJobCosting($lines['unit_job_costing']);
            $quotationLines->setChapter($lines['chapter']);


            $em->persist($quotationLines);
        }
    }

    private function saveQuotationLines(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
