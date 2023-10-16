<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Companies;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Repository\CompaniesRepository;
use App\Entity\Addresses;


class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    public function getCompaniesData(EntityManagerInterface $entityManager, CompaniesRepository $companiesRepository): array
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/companies',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                    // Ajoutez d'autres en-têtes si nécessaire
                ]
            ]
        );

        $data = $response->toArray();

        $arrayNumber = 99;
        $urlAddresses = 'https://axonaut.com/api/v2/companies/{companyId}/addresses';
        

        foreach ($data as $index => $subData) {
            
            if($arrayNumber == 189) {
                $entityManager->flush();
                return $subData;
            }

            // Enregistrement des companies
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

            // Enregistrement des Addresses

            
            $finalUrl = str_replace('{companyId}', $subData['id'], $urlAddresses);
            $responseAddresses = $this->client->request('GET', $finalUrl, [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ]
            ]);

            
            $dataAddresses = $responseAddresses->toArray();

            // dd($dataAddresses[$arrayNumber]);
            foreach ($dataAddresses as $addressesData) {
                $newAddresse = new Addresses();
            
                $newAddresse->setId($addressesData['id']);
                $newAddresse->setName($addressesData['name']);
                $newAddresse->setName($addressesData['name']);
                $newAddresse->setContactName($addressesData['contact_name']);
                $newAddresse->setCompanyName($addressesData['company_name']);
                $newAddresse->setAddressStreet($addressesData['address_street']);
                $newAddresse->setAddressZipCode($addressesData['address_zip_code']);
                $newAddresse->setAddressCity($addressesData['address_city']);
                $newAddresse->setAddressRegion($addressesData['address_region']);
                $newAddresse->setAddressCountry($addressesData['address_country']);
                $newAddresse->setIsForInvoice($addressesData['is_for_invoice']);
                $newAddresse->setIsForDelivery($addressesData['is_for_delivery']);
                $newAddresse->setIsForQuotation($addressesData['is_for_quotation']);
                $newAddresse->setIsForDelivery($addressesData['is_for_delivery']);
        
                $company = $companiesRepository->find($addressesData['company']['id']);
                $newAddresse->setCompanyId($company);

                $entityManager->persist($newAddresse);
            }
            

            
            $arrayNumber++;
        }        
        
        
        $entityManager->flush();
        

        
        return $data;
    }

}