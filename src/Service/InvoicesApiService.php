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

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, InvoicesRepository $invoicesRepository): Response
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

        $this->dataCheck($session, $em, $invoicesRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, InvoicesRepository $invoicesRepository): Response
    {
        $data = $session->get('api_data');

        foreach ($data as $invoicesData) {
            $em->persist($this->invoicesToDatabase($invoicesData, $em));
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

    private function invoicesToDatabase($invoicesData, EntityManagerInterface $em, ?Invoices $invoices = null): Invoices
    {
        $invoicesId = $invoicesData['id'];
        $invoices = $em->getRepository(Invoices::class)->find($invoicesId);

        if ($invoices === null) {
            $invoices = new Invoices();
            $invoices->setId($invoicesId);
        }

        $invoices->setNumber($invoicesData['number']);
        $invoices->setOrderNumber($invoicesData['order_number']);
        $invoices->setDate(new \DateTime($invoicesData['date']));
        $invoices->setSentDate(new \DateTime($invoicesData['sent_date']));
        $invoices->setDueDate(new \DateTime($invoicesData['due_date']));
        $invoices->setPaidDate(new \DateTime($invoicesData['paid_date']));
        $invoices->setDeliveryDate(new \DateTime($invoicesData['delivery_date']));
        $invoices->setLastUpdateDate(new \DateTime($invoicesData['last_update_date']));
        $invoices->setPreTaxAmount($invoicesData['pre_tax_amount']);
        $invoices->setTaxAmount($invoicesData['tax_amount']);
        $invoices->setTotal($invoicesData['total']);
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

        
        
        return $invoices;
    }

    private function saveInvoices(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
