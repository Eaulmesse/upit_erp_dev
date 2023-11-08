<?php

namespace App\Controller;

use App\Entity\SupplierContract;
use App\Repository\ExpensesRepository;
use App\Repository\SupplierContractRepository;
use App\Repository\SuppliersRepository;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SupplierContractController extends AbstractController
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $webhookLogger;
    }

    #[Route('/suppliers-contract/get', name: 'app_get_suppliers_contract')]
    public function callAPI(Request $request, SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, SupplierContractRepository $supplierContractRepository,SuppliersRepository $suppliersRepository, ExpensesRepository $expensesRepository): Response
    {
 
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/supplier-contracts',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                    // Ajoutez d'autres en-têtes si nécessaire
                ]
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);
        //dd($data);
        $this->dataCheck($session, $em, $logger, $supplierContractRepository, $suppliersRepository, $expensesRepository);
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, SupplierContractRepository $supplierContractRepository, SuppliersRepository $suppliersRepository, ExpensesRepository $expensesRepository): Response
    {   
        $data = $session->get('api_data');

        foreach($data as $supplierContractData) {

            $inDatabaseId = $supplierContractRepository->find($supplierContractData['id']);

            if ($inDatabaseId == null) {
                $inDatabaseId = $this->supplierContractToDatabase($supplierContractData, $em, $suppliersRepository, $expensesRepository);
                $em->persist($inDatabaseId);
            }   
            else {
                $inDatabaseId = $this->supplierContractToDatabase($supplierContractData, $em, $suppliersRepository, $expensesRepository);
            } 
        }

        $supplierContractIdsInData = array_map(function($supplierContractData) {
            return $supplierContractData['id'];
        }, $data);

        $allsupplierContract = $supplierContractRepository->findall();
        foreach ($allsupplierContract as $supplier) {
            if (!in_array($supplier->getId(), $supplierContractIdsInData)) {
                $em->remove($supplier);
            }
        }

        $this->saveSupplierContract($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function supplierContractToDatabase($supplierContractData, EntityManagerInterface $em, SuppliersRepository $suppliersRepository, ExpensesRepository $expensesRepository,  ?SupplierContract $suppliers = null): SupplierContract
    {        
       $supplierContract = new SupplierContract;

       $supplierContract->setId($supplierContractData['id']);
       $supplierContract->setTitle($supplierContractData['title']);
       $supplierContract->setSupplier($suppliersRepository->find($supplierContractData['supplier']['id']));

       $startDate = new \DateTime($supplierContractData['start_date']);
       $supplierContract->setStartDate($startDate);

       $endDate = new \DateTime($supplierContractData['end_date']);
       $supplierContract->setEndDate($endDate);

       $supplierContract->setFrequencyInMonths($supplierContractData['frequency_in_months']);
       $supplierContract->setComments($supplierContractData['comments']);

       $supplierContract->setPreTaxAmount($supplierContractData['pre_tax_amount']);

       $supplierContract->setTotalAmount($supplierContractData['total_amount']);

       foreach($supplierContractData['expenses'] as $expense)
       {
            $supplierContract->addExpense($expensesRepository->find($expense['id']));
       }

       return $supplierContract;
    }

    private function saveSupplierContract(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}

