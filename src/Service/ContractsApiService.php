<?php 

namespace App\Service;

use Psr\Log\LoggerInterface;
use App\Repository\UsersRepository;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use App\Repository\QuotationsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Contracts; // Remplacement de Suppliers par Contracts
use App\Repository\ContractsRepository; // Remplacement de SuppliersRepository par ContractsRepository

class ContractsApiService // Remplacement de SuppliersApiService par ContractsApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ContractsRepository $contractsRepository, CompaniesRepository $companyRepository,  AddressesRepository $addressesRepository, UsersRepository $usersRepository, QuotationsRepository $quotationsRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/contracts', // Remplacement de suppliers par contracts
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);
        
        $this->dataCheck($session, $em, $logger, $usersRepository, $contractsRepository, $addressesRepository, $companyRepository, $quotationsRepository);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, UsersRepository $usersRepository, ContractsRepository $contractsRepository,  AddressesRepository $addressesRepository, CompaniesRepository $companyRepository, QuotationsRepository $quotationsRepository): Response
    {   
        $data = $session->get('api_data');

        foreach ($data as $contractsData) {
            $em->persist($this->contractsToDatabase($contractsData, $em, $usersRepository, $companyRepository, $addressesRepository, $quotationsRepository));
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $contractsIdsInData = array_map(function ($contractsData) {
            return $contractsData['id'];
        }, $data);

        $allContracts = $contractsRepository->findAll();
        foreach ($allContracts as $contracts) {
            if (!in_array($contracts->getId(), $contractsIdsInData)) {
                $em->remove($contracts);
            }
        }

        $this->saveContracts($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function contractsToDatabase($contractsData, EntityManagerInterface $em, UsersRepository $usersRepository, CompaniesRepository $companyRepository, AddressesRepository $addressesRepository, QuotationsRepository $quotationsRepository,  ?Contracts $contracts = null): Contracts
    {
        $contractsId = $contractsData['id'];
        $contracts = $em->getRepository(Contracts::class)->find($contractsId);

        if ($contracts === null) {
            $contracts = new Contracts();
            $contracts->setId($contractsId);
        }

        $contracts->setId($contractsData["id"]);
        $contracts->setName($contractsData["name"]);

        $startDate = new \DateTime($contractsData["start_date"]);
        $contracts->setStartDate($startDate);

        $endDate = new \DateTime($contractsData["end_date"]);
        if($endDate !== null) {
            $contracts->setEndDate($endDate);
        } else {
            $endDate = null;
            $contracts->setEndDate($endDate);
        }

        $contracts->setComments($contractsData["comments"]);

        $contractUser = $usersRepository->find($contractsData["user_id"]);
        $contracts->setUser($contractUser);

        $deliveryDate = new \DateTime($contractsData["expected_delivery_date"]);
        if($deliveryDate !== null) {
            $contracts->setExpectedDeliveryDate($deliveryDate);
        } else {
            $deliveryDate = null;
            $contracts->setExpectedDeliveryDate($deliveryDate);
        }    

        $lastUpdateDate = new \DateTime($contractsData["last_update_date"]);
        if($lastUpdateDate !== null) {
            $contracts->setLastUpdateDate($lastUpdateDate);
        } else {
            $lastUpdateDate = null;
            $contracts->setLastUpdateDate($lastUpdateDate);
        }

        $firstInvoicePlannedDate = new \DateTime($contractsData["first_invoice_planned_date"]);
        if($firstInvoicePlannedDate !== null) {
            $contracts->setFirstInvoicePlannedDate($firstInvoicePlannedDate);
        } else {
            $firstInvoicePlannedDate = null;
            $contracts->setFirstInvoicePlannedDate($firstInvoicePlannedDate);
        }  

        $contracts->setGenerateAndSendRecurringInvoices($contractsData["generate_and_send_recurring_invoices"]);
        $contracts->setInvoiceFrenquencyInMonths($contractsData["invoice_frequency_in_months"]);
        $contracts->setPreauthorizedDebit($contractsData["preauthorized_debit"]);
        $contracts->setCompany($companyRepository->find($contractsData['company']['id']));
        $contracts->setGenerateAndSendRecurringInvoices($contractsData["generate_and_send_recurring_invoices"]);

        return $contracts;
    }

    private function saveContracts(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
