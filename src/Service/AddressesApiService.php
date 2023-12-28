<?php 
namespace App\Service;

use App\Entity\Addresses;
use App\Entity\Companies;
use Psr\Log\LoggerInterface;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpClient\HttpClient;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddressesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    
    public function callAPI(EntityManagerInterface $em, LoggerInterface $logger, AddressesRepository $addressesRepository, CompaniesRepository $companiesRepository): Response
    {
        $companies = $companiesRepository->findAll();
        $companyId = null;
        $start = 1;
        $limit = $start + 25;
        $data = array();

        for ($i = $start; $i < $limit; $i++) {

            $companyId = $companies[$i]->getId();

            $response = $this->client->request(
                'GET',
                'https://axonaut.com/api/v2/companies/' . $companyId . '/addresses',
                [
                    'headers' => [
                        'userApiKey' => $_ENV['API_KEY'],
                    ],
                ]
            );
            
            
            $companyData = $response->toArray();
            
            array_push($data, $companyData);
            
        }

        foreach($data as $array) {
            $this->dataCheck($array, $companyId,  $em, $logger, $addressesRepository, $companiesRepository);
        }

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck($array, $companyId, EntityManagerInterface $em, LoggerInterface $logger, AddressesRepository $addressesRepository,  CompaniesRepository $companiesRepository): Response
    {   
        
        $companie = $companiesRepository->find($companyId);
        $grenkeFound = false;
        
        foreach ($array as $addressesData) {

            $addresses = $this->addressesToDatabase($addressesData, $companiesRepository, $em);
            $em->persist($addresses);

            if($addressesData['address_street'] === "54 Rue Marcel Dassault"){
                $grenkeFound = true;
            }
        }

        if(!$grenkeFound) {
            $grenkeAddress = $this->createGrenke($companie);
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        // $addressesIdsInData = array_map(function ($addressesData) {
        //     return $addressesData['id'];
        // }, $data);

        // $allAddresses = $addressesRepository->findAll();
        // foreach ($allAddresses as $addresses) {
        //     if (!in_array($addresses->getId(), $addressesIdsInData)) {
        //         $em->remove($addresses);
        //     }
        // }

        $this->saveAddresses($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function addressesToDatabase($addressesData, CompaniesRepository $companiesRepository,  EntityManagerInterface $em, ?Addresses $addresses = null): Addresses
    {
        $addressesId = $addressesData['id'];
        
        $addresses = $em->getRepository(Addresses::class)->find($addressesId);

        if ($addresses === null) {
            $addresses = new Addresses();
            $addresses->setId($addressesId);
        }

        $addresses->setId($addressesData['id']);
        $addresses->setName($addressesData['name']);
        $addresses->setContactName($addressesData['contact_name']);
        $addresses->setCompanyName($addressesData['company_name']);
        $addresses->setAddressStreet($addressesData['address_street']);
        $addresses->setAddressZipCode($addressesData['address_zip_code']);
        $addresses->setAddressCity($addressesData['address_city']);
        $addresses->setAddressRegion($addressesData['address_region']);
        $addresses->setAddressCountry($addressesData['address_country']);
        $addresses->setIsForInvoice($addressesData['is_for_invoice']);
        $addresses->setIsForDelivery($addressesData['is_for_delivery']);
        $addresses->setIsForQuotation($addressesData['is_for_quotation']);
        $addresses->setIsForDelivery($addressesData['is_for_delivery']);

        $company = $companiesRepository->find($addressesData['company']['id']);
        
        $addresses->setCompanyId($company);

        return $addresses;
    }

    private function saveAddresses(EntityManagerInterface $em): void
    {
        $em->flush();
    }

    public function createGrenke($companie): void {
        print_r("Nouvelle adresse Grenke pour " . $companie->getName());

        $data = [
            "name" => "Grenke",
            "company_id" => $companie->getId(),
            "contact_name" => true,
            "company_name" => $companie->getName(),
            "address_street" => "54 Rue Marcel Dassault",
            "address_zip_code" => "69740",
            "address_city" => "Genas",
            "address_country" => "France",
            "is_for_invoice" => false,
            "is_for_delivery" => false,
            "is_for_quotation" => false
        ];
 
 
        $jsonData = json_encode($data);
        $client = HttpClient::create();
 
        $post = $client->request('POST', 'https://axonaut.com/api/v2/addresses', [
            'headers' => [
                'userApiKey' => $_ENV['API_KEY'],
                'Content-Type' => 'application/json',
            ],
            'body' => $jsonData
        ]);

    }
}