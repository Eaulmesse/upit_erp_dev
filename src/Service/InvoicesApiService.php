<?php

namespace App\Service;

use App\Entity\Invoices;
use App\Repository\InvoicesRepository; 
use App\Repository\ContractsRepository;
use DateTime;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class InvoicesApiService 
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, InvoicesRepository $invoicesRepository, ContractsRepository $contractsRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/invoices', 
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $contractsRepository, $invoicesRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, ContractsRepository $contractsRepository,InvoicesRepository $invoicesRepository): Response
    {
        $data = $session->get('api_data');

        foreach ($data as $invoicesData) {
            $em->persist($this->invoicesToDatabase($invoicesData, $em, $contractsRepository));
        }

        
        $invoicesIdsInData = array_map(function ($invoicesData) {
            return $invoicesData['id'];
        }, $data);

        $allInvoices = $invoicesRepository->findAll();
        foreach ($allInvoices as $invoices) {
            if (!in_array($invoices->getId(), $invoicesIdsInData)) {
                $em->remove($invoices);
            }
        }

        $this->saveInvoices($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function invoicesToDatabase($invoicesData, EntityManagerInterface $em, ContractsRepository $contractsRepository, ?Invoices $invoices = null): Invoices
    {
        $invoicesId = $invoicesData['id'];
        $invoices = $em->getRepository(Invoices::class)->find($invoicesId);

        if ($invoices === null) {
            $invoices = new Invoices();
            $invoices->setId($invoicesId);
        }

        $invoices->setNumber($invoicesData['number']);
        $invoices->setOrderNumber($invoicesData['order_number']);

        if($invoicesData['date']) {
            $invoices->setDate(new \DateTime($invoicesData['date']));
        }

        if($invoicesData['sent_date']) {
            $invoices->setSentDate(new \DateTime($invoicesData['sent_date']));
        }
        
        if($invoicesData['due_date']) {
            $invoices->setDueDate(new \DateTime($invoicesData['due_date']));
        }
        
        if($invoicesData['paid_date']) {
            $invoices->setPaidDate(new \DateTime($invoicesData['paid_date']));
        }
        
        if($invoicesData['delivery_date']) {
            $invoices->setDeliveryDate(new \DateTime($invoicesData['delivery_date']));
        }
        
        if($invoicesData['last_update_date']) {
            $invoices->setLastUpdateDate(new \DateTime($invoicesData['last_update_date']));
        }
        
        $invoices->setPreTaxAmount($invoicesData['pre_tax_amount']);
        $invoices->setTaxAmount($invoicesData['tax_amount']);
        $invoices->setTotal($invoicesData['total']);
        // dd($invoicesData['deposits']['deposit_percent']);
        $invoices->setDepositPercent($invoicesData['deposits']['deposit_percent']);
        $invoices->setDiscountsAmount($invoicesData['discounts']['amount']);
        $invoices->setDiscountsAmountWithTax($invoicesData['discounts']['amount_with_tax']);
        $invoices->setDiscountsComments($invoicesData['discounts']['comments']);
        $invoices->setTaxesRate($invoicesData['taxes'][0]['rate']);
        $invoices->setCurrency($invoicesData['currency']);
        $invoices->setMargin($invoicesData['margin']);
        $invoices->setMandatoryMentions($invoicesData['mandatory_mentions']);
        $invoices->setPaymentMentions($invoicesData['payment_terms']);
        $invoices->setThemeId($invoicesData['theme_id']);
        $invoices->setOutstandingAmount($invoicesData['outstanding_amount']);
        $invoices->setFrequencyInMonths($invoicesData['frequency_in_months']);
        $invoices->setBusinessUser($invoicesData['business_user']);
        $invoices->setPublicPath($invoicesData['public_path']);
        $invoices->setPaidInvoicePdf($invoicesData['paid_invoice_pdf']);
        $invoices->setCustomerPortalUrl($invoicesData['customer_portal_url']);

        if ($invoicesData['contract_id'] !== null) {
            $contract = $contractsRepository->find($invoicesData['contract_id']);

            if ($contract !== null) {
                $invoices->setContracts($contract);
            } 
        } else {
            $invoices->setContracts(null);
        }
                

        
        
        return $invoices;
    }

    private function saveInvoices(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}