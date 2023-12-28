<?php 
namespace App\Service;

use App\Entity\Addresses;
use Psr\Log\LoggerInterface;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AddressesWebhookService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    
    public function getWebhookAddresses($response, SessionInterface $session, LoggerInterface $logger, EntityManagerInterface $em, AddressesRepository $addressesRepository, CompaniesRepository $companiesRepository): Response
    {
    
        $session->set('company_data', $response);
        $this->logger->INFO('ADDRESSES: ', $response);
        $this->AddressesToDatabase($response, $addressesRepository, $logger, $session, $em, $companiesRepository );
        return new Response('Received!', Response::HTTP_OK);
    }

    public function FetchAddressesData($response): array
    {

        $this->logger->INFO('fetch: ', $response);
        $url = 'https://axonaut.com/api/v2/companies/{companyId}/addresses';
        $finalUrl = str_replace('{companyId}', $response['data']['id'], $url);

        $response = $this->client->request('GET', $finalUrl, [
            'headers' => [
                'userApiKey' => $_ENV['API_KEY'],
            ]
        ]);
        return $response->ToArray();
    }

    public function AddressesToDatabase($response, AddressesRepository $addressesRepository, LoggerInterface $logger, SessionInterface $session, EntityManagerInterface $em, CompaniesRepository $companiesRepository): Response {
        
        $dataAPI = $this->FetchAddressesData($response, $addressesRepository, $logger, $session, $em);

        $currentAddresseIds = []; 

        $grenkeFound = false;
        
        foreach ($dataAPI as $addresseData) {
            $this->logger->INFO(' Traitement', $addresseData);
            $addresseFromDb = $addressesRepository->find($addresseData["id"]);
            
            
            // Si l'adresse existe déjà, la mettre à jour
            if ($addresseFromDb) {
                $addresseFromDb = $this->mapToAddressesEntity($addresseData, $addresseFromDb, $companiesRepository);
            } else {
                // Sinon, créer une nouvelle entité adresse
                $addresseFromDb = $this->mapToAddressesEntity($addresseData, $addresseFromDb, $companiesRepository);
                $em->persist($addresseFromDb);
            }
    
            $currentAddresseIds[] = $addresseFromDb->getId();


            if($addresseData['address_street'] === "54 Rue Marcel Dassault") {
                $grenkeFound = true;
                break;
            }
        }

        if(!$grenkeFound) {
            $this->postGrenke($response, $session, $logger, $em, $addressesRepository, $companiesRepository);
        }
        
        // Suppression des adresses non présentes dans les données fraîches de l'API
        $allAddresses = $addressesRepository->find($addresseData['id']);
        foreach ($allAddresses as $addresse) {
            if (!in_array($addresse->getId(), $currentAddresseIds)) {
                $em->remove($addresse);
            }
        }
    
        $this->saveEntities($em);
    
        return new Response('Done', Response::HTTP_OK);
    }
    
    private function mapToAddressesEntity(array $addresseData, ?Addresses $existingAddress = null, CompaniesRepository $companiesRepository): Addresses {
        if (is_null($existingAddress)) {
            $existingAddress = new Addresses();
        }
    
        $this->logger->INFO('Map: ', $addresseData);
    
        $existingAddress->setId($addresseData['id']);
        $existingAddress->setName($addresseData['name']);
        

        $existingAddress->setId($addresseData['id']);
        $existingAddress->setName($addresseData['name']);
        $existingAddress->setContactName($addresseData['contact_name']);
        $existingAddress->setCompanyName($addresseData['company_name']);
        $existingAddress->setAddressStreet($addresseData['address_street']);
        $existingAddress->setAddressZipCode($addresseData['address_zip_code']);
        $existingAddress->setAddressCity($addresseData['address_city']);
        $existingAddress->setAddressRegion($addresseData['address_region']);
        $existingAddress->setAddressCountry($addresseData['address_country']);
        $existingAddress->setIsForInvoice($addresseData['is_for_invoice']);
        $existingAddress->setIsForDelivery($addresseData['is_for_delivery']);
        $existingAddress->setIsForQuotation($addresseData['is_for_quotation']);
        $existingAddress->setIsForDelivery($addresseData['is_for_delivery']);

        $company = $companiesRepository->find($addresseData['company']['id']);
        $existingAddress->setCompanyId($company);
        
        return $existingAddress;
    }


    
    private function saveEntities(EntityManagerInterface $em): void {
        try {
            $em->flush();
        } catch (\Exception $e) {
            $this->logger->error('Data saving error: ', ['error' => $e->getMessage()]);
        }
    }

    public function postGrenke($response, SessionInterface $session, LoggerInterface $logger, EntityManagerInterface $em, AddressesRepository $addressesRepository, CompaniesRepository $companiesRepository): void {
 
        $data = [
            "name" => "Grenke",
            "company_id" => $response['data']['id'],
            "contact_name" => true,
            "company_name" => $response['data']['name'],
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

        $statusCode = $post->getStatusCode();
        if ($statusCode == 200 || $statusCode == 201) {
            $this->getWebhookAddresses($response, $session, $logger, $em, $addressesRepository, $companiesRepository);
        } else {
            // La requête a échoué
        }

    }

}