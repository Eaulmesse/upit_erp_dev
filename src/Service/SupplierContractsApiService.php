<?php

namespace App\Service;

use App\Entity\SupplierContract;
use App\Repository\SupplierContractRepository;
use App\Repository\SuppliersRepository;
use Psr\Log\LoggerInterface;
use App\Repository\UsersRepository;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use App\Repository\QuotationsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\SupplierContracts; // Remplacement de Contracts par SupplierContracts
use App\Repository\SupplierContractsRepository; // Remplacement de ContractsRepository par SupplierContractsRepository

class SupplierContractsApiService // Remplacement de ContractsApiService par SupplierContractsApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, SupplierContractRepository $supplierContractRepository, SuppliersRepository $suppliersRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/supplier-contracts', // Remplacement de contracts par supplier_contracts
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $supplierContractRepository, $suppliersRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger,  SupplierContractRepository $supplierContractRepository, SuppliersRepository $suppliersRepository): Response
    {
        $data = $session->get('api_data');

        foreach ($data as $supplierContractsData) {
            $em->persist($this->supplierContractsToDatabase($supplierContractsData, $em, $suppliersRepository));
        }


        $supplierContractsIdsInData = array_map(function ($supplierContractsData) {
            return $supplierContractsData['id'];
        }, $data);

        $allSupplierContracts = $supplierContractRepository->findAll();
        foreach ($allSupplierContracts as $supplierContracts) {
            if (!in_array($supplierContracts->getId(), $supplierContractsIdsInData)) {
                $em->remove($supplierContracts);
            }
        }

        $this->saveSupplierContracts($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function supplierContractsToDatabase($supplierContractsData, EntityManagerInterface $em, SuppliersRepository $suppliersRepository,  ?SupplierContract $supplierContract = null): SupplierContract
    {
        $supplierContractsId = $supplierContractsData['id'];
        $supplierContract = $em->getRepository(SupplierContract::class)->find($supplierContractsId);

        if ($supplierContract === null) {
            $supplierContract = new SupplierContract();
            $supplierContract->setId($supplierContractsId);
        }

        $supplierContract->setId($supplierContractsData['id']);
        $supplierContract->setTitle($supplierContractsData['title']);
        $supplierContract->setSupplier($suppliersRepository->find($supplierContractsData['supplier']['id']));

        $startDate = new \DateTime($supplierContractsData['start_date']);
        $supplierContract->setStartDate($startDate);

        $endDate = new \DateTime($supplierContractsData['end_date']);
        $supplierContract->setEndDate($endDate);

        $supplierContract->setFrequencyInMonths($supplierContractsData['frequency_in_months']);
        $supplierContract->setComments($supplierContractsData['comments']);

        $supplierContract->setPreTaxAmount($supplierContractsData['pre_tax_amount']);
        $supplierContract->setTotalAmount($supplierContractsData['total_amount']);



        return $supplierContract;
    }

    private function saveSupplierContracts(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
