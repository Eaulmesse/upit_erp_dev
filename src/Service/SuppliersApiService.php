<?php 

namespace App\Service;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Suppliers; // Remplacement de Products par Suppliers
use App\Repository\CompaniesRepository;
use App\Repository\SuppliersRepository; // Remplacement de ProductsRepository par SuppliersRepository

class SuppliersApiService // Remplacement de ProductsApiService par SuppliersApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, SuppliersRepository $suppliersRepository, CompaniesRepository $companiesRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/suppliers', // Remplacement de products par suppliers
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $suppliersRepository, $companiesRepository);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, SuppliersRepository $suppliersRepository, CompaniesRepository $companiesRepository): Response
    {   
        $data = $session->get('api_data');

        foreach ($data as $suppliersData) {
            $suppliers = $this->suppliersToDatabase($suppliersData, $em, $suppliersRepository, $companiesRepository);
            $em->persist($suppliers);
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $suppliersIdsInData = array_map(function ($suppliersData) {
            return $suppliersData['id'];
        }, $data);

        $allSuppliers = $suppliersRepository->findAll();
        foreach ($allSuppliers as $suppliers) {
            if (!in_array($suppliers->getId(), $suppliersIdsInData)) {
                $em->remove($suppliers);
            }
        }

        $this->saveSuppliers($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function suppliersToDatabase($suppliersData, EntityManagerInterface $em, SuppliersRepository $suppliersRepository, CompaniesRepository $companiesRepository, ?Suppliers $suppliers = null): Suppliers
    {
        $suppliersId = $suppliersData['id'];
        $suppliers = $em->getRepository(Suppliers::class)->find($suppliersId);

        if ($suppliers === null) {
            $suppliers = new Suppliers();
            $suppliers->setId($suppliersId);
        }

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
