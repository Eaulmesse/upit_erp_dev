<?php

namespace App\Service;

use App\Entity\Invoices;
use App\Entity\Companies;
use Psr\Log\LoggerInterface;
use App\Repository\InvoicesRepository;
use App\Repository\ProductsRepository;
use App\Repository\TaxRatesRepository;
use App\Repository\ContractsRepository;
use App\Service\InvoiceLinesApiService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InvoiceLinesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class InvoicesWebhookService
{
    private $logger;

    public function __construct(LoggerInterface $webhookLogger)
    {
        $this->logger = $webhookLogger;
    }

    public function getWebhookInvoices(Request $request, SessionInterface $session, EntityManagerInterface $em, ContractsRepository $contractsRepository, InvoiceLinesApiService $invoiceLinesApiService, InvoiceLinesRepository $invoiceLinesRepository, InvoicesRepository $invoicesRepository, ProductsRepository $productsRepository, TaxRatesRepository $taxRatesRepository): Response
    {
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }

        $session->set('webhook_data', $response);
        
        $this->logger->info('Webhook Invoices received!', $response);

        $this->creatingInvoices($session, $em, $contractsRepository, $invoiceLinesApiService, $invoiceLinesRepository, $invoicesRepository, $productsRepository, $taxRatesRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function creatingInvoices(SessionInterface $session, EntityManagerInterface $em, ContractsRepository $contractsRepository, InvoiceLinesApiService $invoiceLinesApiService, InvoiceLinesRepository $invoiceLinesRepository, InvoicesRepository $invoicesRepository, ProductsRepository $productsRepository, TaxRatesRepository $taxRatesRepository): void
    {
        $webhookData = $session->get('webhook_data');
        $this->logger->INFO('Création: ', $webhookData);

        $invoices = new Invoices();
        $this->mapToDatabase($webhookData, $invoices, $contractsRepository);
        $em->persist($invoices);
        $em->flush();

        $invoicesData = $webhookData['data'];
        $invoiceLinesApiService->getData($session, $em, $invoicesData, $invoiceLinesRepository, $invoicesRepository, $productsRepository, $taxRatesRepository);
    }

    public function mapToDatabase($webhookData, Invoices $invoices, ContractsRepository $contractsRepository): Invoices 
    {
        $invoices->setId($webhookData['data']['id']);
        $invoices->setNumber($webhookData['data']['number']);
        $invoices->setOrderNumber($webhookData['data']['order_number']);

        if($webhookData['data']['date']) {
            $invoices->setDate(new \DateTime($webhookData['data']['date']));
        }

        if($webhookData['data']['sent_date']) {
            $invoices->setSentDate(new \DateTime($webhookData['data']['sent_date']));
        }
        
        if($webhookData['data']['due_date']) {
            $invoices->setDueDate(new \DateTime($webhookData['data']['due_date']));
        }
        
        if($webhookData['data']['paid_date']) {
            $invoices->setPaidDate(new \DateTime($webhookData['data']['paid_date']));
        }
        
        if($webhookData['data']['delivery_date']) {
            $invoices->setDeliveryDate(new \DateTime($webhookData['data']['delivery_date']));
        }
        
        if($webhookData['data']['last_update_date']) {
            $invoices->setLastUpdateDate(new \DateTime($webhookData['data']['last_update_date']));
        }
        
        $invoices->setPreTaxAmount($webhookData['data']['pre_tax_amount']);
        $invoices->setTaxAmount($webhookData['data']['tax_amount']);
        $invoices->setTotal($webhookData['data']['total']);
        // dd($webhookData['data']['deposits']['deposit_percent']);
        $invoices->setDepositPercent($webhookData['data']['deposits']['deposit_percent']);
        $invoices->setDiscountsAmount($webhookData['data']['discounts']['amount']);
        $invoices->setDiscountsAmountWithTax($webhookData['data']['discounts']['amount_with_tax']);
        $invoices->setDiscountsComments($webhookData['data']['discounts']['comments']);
        $invoices->setTaxesRate($webhookData['data']['taxes'][0]['rate']);
        $invoices->setCurrency($webhookData['data']['currency']);
        $invoices->setMargin($webhookData['data']['margin']);
        $invoices->setMandatoryMentions($webhookData['data']['mandatory_mentions']);
        $invoices->setPaymentMentions($webhookData['data']['payment_terms']);
        $invoices->setThemeId($webhookData['data']['theme_id']);
        $invoices->setOutstandingAmount($webhookData['data']['outstanding_amount']);
        $invoices->setFrequencyInMonths($webhookData['data']['frequency_in_months']);
        $invoices->setBusinessUser($webhookData['data']['business_user']);
        $invoices->setPublicPath($webhookData['data']['public_path']);
        $invoices->setPaidInvoicePdf($webhookData['data']['paid_invoice_pdf']);
        $invoices->setCustomerPortalUrl($webhookData['data']['customer_portal_url']);

        if ($webhookData['data']['contract_id'] !== null) {
            $contract = $contractsRepository->find($webhookData['data']['contract_id']);

            if ($contract !== null) {
                $invoices->setContracts($contract);
            } 
        } else {
            $invoices->setContracts(null);
        }
        


        return $invoices;
    }


}




