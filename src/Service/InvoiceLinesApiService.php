<?php

namespace App\Service;

use App\Entity\InvoiceLines; // Remplace ExpenseLines
use Psr\Log\LoggerInterface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\InvoiceLinesRepository; // Remplace ExpenseLinesRepository
use App\Repository\InvoicesRepository;
use App\Repository\ProductsRepository;
use App\Repository\TaxRatesRepository;

class InvoiceLinesApiService // Remplace ExpenseLinesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function getData(SessionInterface $session, EntityManagerInterface $em, $invoicesData,  InvoiceLinesRepository $invoiceLinesRepository, InvoicesRepository $invoicesRepository, ProductsRepository $productsRepository, TaxRatesRepository $taxRatesRepository): Response
    {
        $session->set('expense_data', $invoicesData);

        $this->dataCheck($session, $em, $invoiceLinesRepository, $invoicesRepository, $productsRepository, $taxRatesRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, InvoiceLinesRepository $invoiceLinesRepository, InvoicesRepository $invoicesRepository, ProductsRepository $productsRepository, TaxRatesRepository $taxRatesRepository): Response
    {
        $invoicesData = $session->get("expense_data");

        $this->invoiceLinesToDatabase($invoicesData, $em, $invoicesRepository, $productsRepository, $taxRatesRepository);

        $this->saveInvoiceLines($em); 

        return new Response('Received!', Response::HTTP_OK);
    }

    private function invoiceLinesToDatabase($invoicesData, EntityManagerInterface $em, InvoicesRepository $invoicesRepository, ProductsRepository $productsRepository, TaxRatesRepository $taxRatesRepository, ?InvoiceLines $invoiceLines = null): void
    {
        
        $invoiceLinesData = $invoicesData["invoice_lines"]; 

        foreach ($invoiceLinesData as $lines) {
            $invoiceLines = new InvoiceLines(); 
            $invoiceLines->setInvoice($invoicesRepository->find($invoicesData["id"]));
            $invoiceLines->setProduct($productsRepository->find($lines["product_id"]));
            $invoiceLines->setTaxRates($taxRatesRepository->find($lines["tax_rates"][0]["id"]));
            $invoiceLines->setDetails($lines["details"]);
            $invoiceLines->setTotalPreTaxAmount($lines["total_pre_tax_amount"]);
            $invoiceLines->setTotalTaxAmount($lines["total_tax_amount"]);
            $invoiceLines->setTotalAmount($lines["total_amount"]);
            $invoiceLines->setChapter($lines["chapter"]);
            $invoiceLines->setChapter($lines["chapter"]);
            $invoiceLines->setDiscountsAmount($lines["discounts"]["amount"]);
            $invoiceLines->setDiscountsAmountWithTax($lines["discounts"]["amount_with_tax"]);
            $invoiceLines->setAccountingCode($lines["accounting_code"]);
            $invoiceLines->setUnitJobCosting($lines["unit_job_costing"]);
            

            $em->persist($invoiceLines);
        }
    }

    private function saveInvoiceLines(EntityManagerInterface $em): void // Remplace saveExpenseLines
    {
        $em->flush();
    }
}
