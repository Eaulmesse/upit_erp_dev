<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\AddressesRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompaniesRepository;
use Doctrine\ORM\EntityManager;
use App\Entity\Addresses;


class AddressesController extends AbstractController
{
    private $logger;
    private $client;


    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
    }

    
    public function GetWebhookFromCompanies($responseData, AddressesRepository $addressesRepository, LoggerInterface $logger, SessionInterface $session, EntityManagerInterface $em, CompaniesRepository $companiesRepository): Response
    {
        $session->set('company_data', $responseData);
        $this->AddressesToDatabase($responseData, $addressesRepository, $logger, $session, $em, $companiesRepository );
        
        $this->logger->INFO('In Addresses: ', $responseData);
        return new Response('Received!', Response::HTTP_OK);
    }

    public function FetchAddressesData($responseData, AddressesRepository $addressesRepository, LoggerInterface $logger, SessionInterface $session): array
    {

        $this->logger->INFO('fetch: ', $responseData);
        $url = 'https://axonaut.com/api/v2/companies/{companyId}/addresses';
        $finalUrl = str_replace('{companyId}', $responseData['data']['id'], $url);

        $response = $this->client->request('GET', $finalUrl, [
            'headers' => [
                'userApiKey' => $_ENV['API_KEY'],
            ]
        ]);
        return $response->ToArray();
    }

    

    public function AddressesToDatabase($responseData, AddressesRepository $addressesRepository, LoggerInterface $logger, SessionInterface $session, EntityManagerInterface $em, CompaniesRepository $companiesRepository): Response {
        $dataAPI = $this->FetchAddressesData($responseData, $addressesRepository, $logger, $session, $em);

        $currentAddresseIds = []; 
        
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

    // public function isValidAddresses(array $addresses, AddressesRepository $addressesRepository): bool
    // {
    //     $this->logger->INFO('Is valid: ', $addresses);
    //     return is_null($addressesRepository->find($addresses["id"])) && $addresses['company_name'] !== null;
    // }

}

