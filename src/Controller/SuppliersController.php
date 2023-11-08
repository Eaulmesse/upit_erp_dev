<?php

namespace App\Controller;

use App\Entity\Suppliers;
use Psr\Log\LoggerInterface;
use App\Repository\ExpensesRepository;
use App\Repository\PayslipsRepository;
use App\Repository\ProjectsRepository;
use App\Repository\CompaniesRepository;
use App\Repository\SuppliersRepository;
use App\Repository\WorkforcesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SuppliersController extends AbstractController
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $webhookLogger;
    }

    #[Route('/suppliers/get', name: 'app_get_suppliers')]
    public function callAPI(Request $request, SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, SuppliersRepository $suppliersRepository, CompaniesRepository $companiesRepository): Response
    {
 
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/suppliers',
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
        $this->dataCheck($session, $em, $logger, $suppliersRepository, $companiesRepository);
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, SuppliersRepository $suppliersRepository, CompaniesRepository $companiesRepository): Response
    {   
        $data = $session->get('api_data');

        foreach($data as $suppliersData) {

            $inDatabaseId = $suppliersRepository->find($suppliersData['id']);

            if ($inDatabaseId == null) {
                $inDatabaseId = $this->suppliersToDatabase($suppliersData, $em, $suppliersRepository, $companiesRepository);
                $em->persist($inDatabaseId);
            }   
            else {
                $inDatabaseId = $this->suppliersToDatabase($suppliersData, $em, $suppliersRepository, $companiesRepository);
            } 
        }

        $suppliersIdsInData = array_map(function($suppliersData) {
            return $suppliersData['id'];
        }, $data);

        $allsuppliers = $suppliersRepository->findall();
        foreach ($allsuppliers as $supplier) {
            if (!in_array($supplier->getId(), $suppliersIdsInData)) {
                $em->remove($supplier);
            }
        }

        $this->saveSuppliers($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function suppliersToDatabase($suppliersData, EntityManagerInterface $em, SuppliersRepository $supplierRepository, CompaniesRepository $companiesRepository, ?Suppliers $suppliers = null): Suppliers
    {        
       $suppliers = new Suppliers;

       $suppliers->setId($suppliersData['id']);
       $suppliers->setName($suppliersData['name']);
       $suppliers->setPreferedTaxRate($suppliersData['prefered_tax_rate']);
       $suppliers->setCompany($companiesRepository->find($suppliersData['company_id']));
       $suppliers->setThirdpartyCode($suppliersData['thirdparty_code']);
       
       return $suppliers;
    }

    private function saveSuppliers(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
