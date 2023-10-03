<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Companies;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    public function getCompaniesData(EntityManagerInterface $entityManager): array
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/companies',
            [
                'headers' => [
                    'userApiKey' => '95463d656ce0e052636fe6cf64bc288e',
                    // Ajoutez d'autres en-têtes si nécessaire
                ]
            ]
        );

        $data = $response->toArray();

        foreach ($data as $index => $subData) {
            
            $dataCompanies = new Companies();
            $dataCompanies->setId($subData["id"]);
            $dataCompanies->setName($subData["name"]);

            $creationDate = new \DateTime($subData["creation_date"]);
            $dataCompanies->setCreationDate($creationDate);


            $dataCompanies->setAddressStreet($subData["address_street"]);
            $dataCompanies->setAddressZipCode(intval($subData["address_zip_code"]));
            $dataCompanies->setAddressCity($subData["address_city"]);
            $dataCompanies->setAddressRegion($subData["address_region"]);
            $dataCompanies->setAddressCountry($subData["address_country"]);
            $dataCompanies->setComments($subData["comments"]);
            $dataCompanies->setIsSupplier($subData["is_supplier"]);
            $dataCompanies->setIsProspect($subData["is_prospect"]);
            $dataCompanies->setIsCustomer($subData["is_customer"]);
            $dataCompanies->setCurrency($subData["currency"]);
            $dataCompanies->setLanguage($subData["language"]);
            $dataCompanies->setThirdpartyCode($subData["thirdparty_code"]);
            $dataCompanies->setIntracommunityNumber(intval($subData["intracommunity_number"]));
            $dataCompanies->setSupplierThidpartyCode($subData["supplier_thirdparty_code"]);

            

            $dataCompanies->setSiret(intval($subData["siret"]));
            $dataCompanies->setIsB2C(boolval($subData["isB2C"]));
                
            $dataCompaniesArray[] = $dataCompanies;
            $entityManager->persist($dataCompanies);
        }
        
        $entityManager->flush();
        
        return $data;
    }
}