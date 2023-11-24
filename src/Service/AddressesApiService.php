<?php 
namespace App\Service;

use App\Entity\Addresses;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Companies;

use App\Repository\CompaniesRepository;
use App\Repository\AddressesRepository;

class AddressesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    
    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, AddressesRepository $addressesRepository, CompaniesRepository $companiesRepository): Response
    {
        $companies = $companiesRepository->findAll();

        foreach($companies as $company) {
            $companyId = $company->getId();

            $response = $this->client->request(
                'GET',
                'https://axonaut.com/api/v2/companies/' . $companyId . '/addresses',
                [
                    'headers' => [
                        'userApiKey' => $_ENV['API_KEY'],
                    ],
                    'query' => [
                        'limit' => 10,  // Remplacez 10 par le nombre que vous souhaitez
                    ],
                ]
            );

            $data = $response->toArray();

            $session->set('api_data', $data);

            $this->dataCheck($session, $em, $logger, $addressesRepository,$companiesRepository);
        }
    
        
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, AddressesRepository $addressesRepository,  CompaniesRepository $companiesRepository): Response
    {   
        $data = $session->get('api_data');

        foreach ($data as $addressesData) {
            $addresses = $this->addressesToDatabase($addressesData, $companiesRepository, $em);
            $em->persist($addresses);
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $addressesIdsInData = array_map(function ($addressesData) {
            return $addressesData['id'];
        }, $data);

        $allAddresses = $addressesRepository->findAll();
        foreach ($allAddresses as $addresses) {
            if (!in_array($addresses->getId(), $addressesIdsInData)) {
                $em->remove($addresses);
            }
        }

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
}