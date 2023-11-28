<?php

namespace App\Service;

use App\Entity\Companies;
use Psr\Log\LoggerInterface;
use App\Repository\CompaniesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CompaniesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    
    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, CompaniesRepository $companiesRepository): Response
    {
 
        $page = 1;
        // $limit = 500; 

        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/companies',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                    'page' => $page,        // Utilisez l'en-tête personnalisé pour la pagination
                    // 'per_page' => $limit,  
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);
        
        $this->dataCheck($session, $em, $logger, $companiesRepository);
        $numberOfData = count($data);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, CompaniesRepository $companiesRepository): Response
    {   
        $data = $session->get('api_data');

        foreach ($data as $companyData) {
            $company = $this->CompanyToDatabase($companyData, $em);
            $em->persist($company);
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        // $companyIdsInData = array_map(function ($companyData) {
        //     return $companyData['id'];
        // }, $data);

        // $allCompanies = $companiesRepository->findAll();
        // foreach ($allCompanies as $company) {
        //     if (!in_array($company->getId(), $companyIdsInData)) {
        //         $em->remove($company);
        //     }
        // }

        $this->saveCompany($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function CompanyToDatabase($companyData, EntityManagerInterface $em, ?Companies $companies = null): Companies
    {

        $companyId = $companyData['id'];
        $company = $em->getRepository(Companies::class)->find($companyId);

        if ($company === null) {
            // L'entité n'existe pas dans la base de données, donc nous la créons
            $company = new Companies();
            $company->setId($companyId);
        }
        

        $company->setId($companyData["id"]);
        $company->setName($companyData["name"]);

        $creationDate = new \DateTime($companyData["creation_date"]);
        $company->setCreationDate($creationDate);

        $company->setAddressStreet($companyData["address_street"]);
        $company->setAddressZipCode(intval($companyData["address_zip_code"]));
        $company->setAddressCity($companyData["address_city"]);
        $company->setAddressRegion($companyData["address_region"]);
        $company->setAddressCountry($companyData["address_country"]);
        $company->setComments($companyData["comments"]);
        $company->setIsSupplier($companyData["is_supplier"]);
        $company->setIsProspect($companyData["is_prospect"]);
        $company->setIsCustomer($companyData["is_customer"]);
        $company->setCurrency($companyData["currency"]);
        $company->setLanguage($companyData["language"]);
        $company->setThirdpartyCode($companyData["thirdparty_code"]);
        $company->setIntracommunityNumber(intval($companyData["intracommunity_number"]));
        $company->setSupplierThidpartyCode($companyData["supplier_thirdparty_code"]);
        $company->setSiret(intval($companyData["siret"]));
        $company->setIsB2C(boolval($companyData["isB2C"]));


        return $company;
    }

    private function saveCompany(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}