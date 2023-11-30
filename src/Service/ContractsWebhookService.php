<?php

namespace App\Service;

use App\Entity\Contracts;
use App\Repository\CompaniesRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\ContractsRepository;
use App\Repository\UsersRepository;
use App\Repository\AddressesRepository;
use App\Repository\QuotationsRepository;


class ContractsWebhookService
{

    private $logger;
    private $client;
    

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
    }


    
    public function getWebhookContracts(Request $request, SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ContractsRepository $contractsRepository, UsersRepository $usersRepository, CompaniesRepository $companyRepository, AddressesRepository $addressesRepository, QuotationsRepository $quotationsRepository): Response
    {
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }

        $session->set('webhook_data', $response);
        
        $this->logger->info('Webhook Employees received!', $response);

        $this->webhookContractsFilter($session, $em, $logger, $contractsRepository, $usersRepository, $companyRepository, $addressesRepository, $quotationsRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function createContract($webhookData, EntityManagerInterface $em, ContractsRepository $contractsRepository, UsersRepository $usersRepository, CompaniesRepository $companyRepository, AddressesRepository $addressesRepository, QuotationsRepository $quotationsRepository): void {

        $this->logger->info('Creation contract', $webhookData);
        $contract = new Contracts();
        $this->mapDataToContract($contract, $webhookData, $contractsRepository, $usersRepository, $companyRepository, $addressesRepository, $quotationsRepository);
        $em->persist($contract);
        $em->flush();
    }

    private function updateContract($webhookData, EntityManagerInterface $em, ContractsRepository $contractsRepository, UsersRepository $usersRepository, CompaniesRepository $companyRepository, AddressesRepository $addressesRepository, QuotationsRepository $quotationsRepository): void {

        $this->logger->info('Update contract', $webhookData);
        $contract = $contractsRepository->find($webhookData["data"]["id"]);
        if (!$contract) {
            throw new \Exception("Quotation with ID " . $webhookData["data"]["id"] . " not found.");
        }
        $this->mapDataToContract($contract, $webhookData, $contractsRepository, $usersRepository, $companyRepository, $addressesRepository, $quotationsRepository);
        $em->flush();
    }

    private function deleteContract($webhookData, EntityManagerInterface $em, ContractsRepository $contractsRepository): void {

        $this->logger->INFO('Suppression: ', $webhookData);
        
        $contract = $contractsRepository->find($webhookData["data"]["id"]);
        $em->remove($contract);
        $em->flush();
    }

    private function mapDataToContract(Contracts $contract, $webhookData, ContractsRepository $contractsRepository, UsersRepository $usersRepository, CompaniesRepository $companyRepository, AddressesRepository $addressesRepository, QuotationsRepository $quotationsRepository): void {

        $contract->setId($webhookData["data"]["id"]);
        $contract->setName($webhookData["data"]["name"]);

        $startDate = new \DateTime($webhookData["data"]["start_date"]);
        $contract->setStartDate($startDate);

        $endDate = new \DateTime($webhookData['data']["end_date"]);
        if($endDate !== null) {
            $contract->setEndDate($endDate);
        } else {
            $endDate = null;
            $contract->setEndDate($endDate);
        }

        $contract->setComments($webhookData["data"]["comments"]);

        $contractUser = $usersRepository->find($webhookData["data"]["user_id"]);
        $contract->setUser($contractUser);

        $deliveryDate = new \DateTime($webhookData['data']["expected_delivery_date"]);
        if($deliveryDate !== null) {
            $contract->setExpectedDeliveryDate($deliveryDate);
        } else {
            $deliveryDate = null;
            $contract->setExpectedDeliveryDate($deliveryDate);
        }    

        $lastUpdateDate = new \DateTime($webhookData['data']["last_update_date"]);
        if($lastUpdateDate !== null) {
            $contract->setLastUpdateDate($lastUpdateDate);
        } else {
            $lastUpdateDate = null;
            $contract->setLastUpdateDate($lastUpdateDate);
        }  

        $invoiceAddresse = $addressesRepository->findOneBy([
            'company_id' => ["data"]["company"]["id"],
            'is_for_invoice' => 'true'
        ]);

        if($invoiceAddresse !== null)
        {
            $contract->setInvoiceAddressStreet($invoiceAddresse->getAddressStreet());
            $contract->setInvoiceAddressCity($invoiceAddresse->getAddressCity());
        }

        $deliveryAddresse = $addressesRepository->findOneBy([
            'company_id' => ["data"]["company"]["id"],
            'is_for_delivery' => 'true'
        ]);

        if($deliveryAddresse !== null)
        {
            $contract->setDeliveryAddressStreet($deliveryAddresse->getAddressStreet());
            $contract->setDeliveryAddressCity($deliveryAddresse->getAddressCity());
        }

        $firstInvoicePlannedDate = new \DateTime($webhookData['data']["first_invoice_planned_date"]);
        if($firstInvoicePlannedDate !== null) {
            $contract->setFirstInvoicePlannedDate($firstInvoicePlannedDate);
        } else {
            $firstInvoicePlannedDate = null;
            $contract->setFirstInvoicePlannedDate($firstInvoicePlannedDate);
        }  

        $contract->setGenerateAndSendRecurringInvoices($webhookData["data"]["generate_and_send_recurring_invoices"]);
        $contract->setInvoiceFrenquencyInMonths($webhookData["data"]["invoice_frequency_in_months"]);
        $contract->setPreauthorizedDebit($webhookData["data"]["preauthorized_debit"]);
        $contract->setCompany($companyRepository->find(['data']['company']['id']));
        $contract->setGenerateAndSendRecurringInvoices($webhookData["data"]["generate_and_send_recurring_invoices"]);

    }

    #[Route('/webhook/contracts/filter', name: 'app_webhook_contracts_filter')]
    public function webhookContractsFilter(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ContractsRepository $contractsRepository, UsersRepository $usersRepository, CompaniesRepository $companyRepository, AddressesRepository $addressesRepository, QuotationsRepository $quotationsRepository): Response 
    {
        $webhookData = $session->get('webhook_data');
        
        switch ($webhookData['topic']) {
            case 'contract.created':
                $this->createContract($webhookData, $em, $contractsRepository, $usersRepository, $companyRepository, $addressesRepository, $quotationsRepository);
                break;
            case 'contract.updated':
                $this->updateContract($webhookData, $em, $contractsRepository, $usersRepository, $companyRepository, $addressesRepository, $quotationsRepository);
                break;
            case 'contract.deleted':
                $this->deleteContract($webhookData, $em, $contractsRepository);
                break;
        }
        return new Response(' Done!', Response::HTTP_OK);
    }
}
