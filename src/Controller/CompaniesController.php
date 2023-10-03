<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Companies;
use App\Entity\Addresses;
use App\Repository\CompaniesRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\AddressesRepository;

class CompaniesController extends AbstractController
{

    private $logger;
    private $client;


    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
    }
    
    #[Route('/webhook/companies', name: 'app_webhook_companies', methods: 'POST')]
    public function getWebhookCompanies(Request $request, SessionInterface $session, EntityManagerInterface $em, CompaniesRepository $companiesRepository,AddressesRepository $addressesRepository, LoggerInterface $logger): Response
    {
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }
        
        // Traitez vos données ici (par exemple, stockez-les dans une base de données, etc.)


        $session->set('webhook_data', $response);
        

        // Mise en log webhook.log
        $this->logger->info('Webhook received!', $response);



        $this->webhookCompaniesFilter($session, $em, $companiesRepository,$addressesRepository, $logger);


        return new Response('Received!', Response::HTTP_OK);
    }

    public function creatingCompanies(SessionInterface $session, EntityManagerInterface $em): Response
    {

        $webhookData = $session->get('webhook_data');

        $this->logger->INFO('Création: ', $webhookData);

            
        // Création du nouvel objet Companies
        $dataCompanies = new Companies();

        $dataCompanies->setId($webhookData["data"]["id"]);
        $dataCompanies->setName($webhookData["data"]["name"]);

        $creationDate = new \DateTime($webhookData["data"]["creation_date"]);
        $dataCompanies->setCreationDate($creationDate);

        $dataCompanies->setAddressStreet($webhookData["data"]["address_street"]);
        $dataCompanies->setAddressZipCode(intval($webhookData["data"]["address_zip_code"]));
        $dataCompanies->setAddressCity($webhookData["data"]["address_city"]);
        $dataCompanies->setAddressRegion($webhookData["data"]["address_region"]);
        $dataCompanies->setAddressCountry($webhookData["data"]["address_country"]);
        $dataCompanies->setComments($webhookData["data"]["comments"]);
        $dataCompanies->setIsSupplier($webhookData["data"]["is_supplier"]);
        $dataCompanies->setIsProspect($webhookData["data"]["is_prospect"]);
        $dataCompanies->setIsCustomer($webhookData["data"]["is_customer"]);
        $dataCompanies->setCurrency($webhookData["data"]["currency"]);
        $dataCompanies->setLanguage($webhookData["data"]["language"]);
        $dataCompanies->setThirdpartyCode($webhookData["data"]["thirdparty_code"]);
        $dataCompanies->setIntracommunityNumber(intval($webhookData["data"]["intracommunity_number"]));
        $dataCompanies->setSupplierThidpartyCode($webhookData["data"]["supplier_thirdparty_code"]);
        $dataCompanies->setSiret(intval($webhookData["data"]["siret"]));
        $dataCompanies->setIsB2C(boolval($webhookData["data"]["isB2C"]));

        $em->persist($dataCompanies);


        // Création du nouvel objet Addresses
        $link = "https://axonaut.com/api/v2/companies/" . $webhookData["data"]["id"] . "/addresses";
        $response = $this->client->request(
            'GET',
            $link,
            [
                'headers' => [
                    'userApiKey' => '95463d656ce0e052636fe6cf64bc288e',
                ]
            ]
        );
        $addresses = $response->toArray();

        foreach($addresses as $index => $subAddresses) {
            $dataAdresses = new Addresses();

            $dataAdresses->setCompanyId($dataCompanies);
            $dataAdresses->setName($subAddresses['name']);
            $dataAdresses->setContactName($subAddresses['contact_name']);
            $dataAdresses->setCompanyName($subAddresses['company_name']);
            $dataAdresses->setAddressStreet($subAddresses['address_street']);

            $dataAdresses->setAddressZipCode($subAddresses['address_zip_code']);
            $dataAdresses->setAddressCity($subAddresses['address_city']);
            $dataAdresses->setAddressRegion($subAddresses['address_region']);
            $dataAdresses->setAddressCountry(($subAddresses['address_country']));

            $dataAdresses->setIsForInvoice($subAddresses['is_for_invoice']);
            $dataAdresses->setIsForDelivery($subAddresses['is_for_delivery']);

            $dataAdresses->setIsForQuotation($subAddresses['is_for_quotation']);
            $dataAdresses->setIsForDelivery($subAddresses['is_for_delivery']);

            $em->persist($dataAdresses);
        }
           
                
        try{
            $em->flush();
        }
        catch(\Exception $e){
            error_log($e->getMessage());
        }


        return new Response(' Done!', Response::HTTP_OK);
    }

    public function updatingCompanies(SessionInterface $session, EntityManagerInterface $em,  CompaniesRepository $companiesRepository, AddressesRepository $addressesRepository): Response
    {
        $webhookData = $session->get('webhook_data');
        $this->logger->INFO('Modification: ', $webhookData);

        $updatedCompanie = $companiesRepository->find($webhookData["data"]["id"]);

        $em->remove($updatedCompanie);
        $em->flush();

        $dataCompanies = new Companies();

        $dataCompanies->setId($webhookData["data"]["id"]);
        $dataCompanies->setName($webhookData["data"]["name"]);

        $creationDate = new \DateTime($webhookData["data"]["creation_date"]);
        $dataCompanies->setCreationDate($creationDate);

        $dataCompanies->setAddressStreet($webhookData["data"]["address_street"]);
        $dataCompanies->setAddressZipCode(intval($webhookData["data"]["address_zip_code"]));
        $dataCompanies->setAddressCity($webhookData["data"]["address_city"]);
        $dataCompanies->setAddressRegion($webhookData["data"]["address_region"]);
        $dataCompanies->setAddressCountry($webhookData["data"]["address_country"]);
        $dataCompanies->setComments($webhookData["data"]["comments"]);
        $dataCompanies->setIsSupplier($webhookData["data"]["is_supplier"]);
        $dataCompanies->setIsProspect($webhookData["data"]["is_prospect"]);
        $dataCompanies->setIsCustomer($webhookData["data"]["is_customer"]);
        $dataCompanies->setCurrency($webhookData["data"]["currency"]);
        $dataCompanies->setLanguage($webhookData["data"]["language"]);
        $dataCompanies->setThirdpartyCode($webhookData["data"]["thirdparty_code"]);
        $dataCompanies->setIntracommunityNumber(intval($webhookData["data"]["intracommunity_number"]));
        $dataCompanies->setSupplierThidpartyCode($webhookData["data"]["supplier_thirdparty_code"]);
        $dataCompanies->setSiret(intval($webhookData["data"]["siret"]));
        $dataCompanies->setIsB2C(boolval($webhookData["data"]["isB2C"]));
        
        $em->persist($dataCompanies);
        $em->flush();

        $updatedAddresses = $addressesRepository->findAll();
            
        foreach($updatedAddresses as $subUpdatedAddresses) {
            $company = $subUpdatedAddresses->getCompanyId();
        
            if ($company && $company->getId() == $webhookData["data"]["id"]) {
                $em->remove($subUpdatedAddresses);
            }
        }

       // Création du nouvel objet Addresses
       $link = "https://axonaut.com/api/v2/companies/" . $webhookData["data"]["id"] . "/addresses";
       $response = $this->client->request(
           'GET',
           $link,
           [
               'headers' => [
                   'userApiKey' => '95463d656ce0e052636fe6cf64bc288e',
               ]
           ]
        );

        $addresses = $response->toArray();

        foreach($addresses as $index => $subAddresses) {
            $dataAdresses = new Addresses();

            $dataAdresses->setCompanyId($dataCompanies);
            $dataAdresses->setName($subAddresses['name']);
            $dataAdresses->setContactName($subAddresses['contact_name']);
            $dataAdresses->setCompanyName($subAddresses['company_name']);
            $dataAdresses->setAddressStreet($subAddresses['address_street']);

            $dataAdresses->setAddressZipCode($subAddresses['address_zip_code']);
            $dataAdresses->setAddressCity($subAddresses['address_city']);
            $dataAdresses->setAddressRegion($subAddresses['address_region']);
            $dataAdresses->setAddressCountry(($subAddresses['address_country']));

            $dataAdresses->setIsForInvoice($subAddresses['is_for_invoice']);
            $dataAdresses->setIsForDelivery($subAddresses['is_for_delivery']);

            $dataAdresses->setIsForQuotation($subAddresses['is_for_quotation']);
            $dataAdresses->setIsForDelivery($subAddresses['is_for_delivery']);

            $em->persist($dataAdresses);
        }

        try{
            $em->flush();
        }
        catch(\Exception $e){
            error_log($e->getMessage());
        }

        

    return new Response(' Done!', Response::HTTP_OK);
    }

    public function deletingCompanies(SessionInterface $session, EntityManagerInterface $em,  CompaniesRepository $companiesRepository, AddressesRepository $addressesRepository): Response
    {
        $webhookData = $session->get('webhook_data');

        $this->logger->INFO('Suppression: ', $webhookData);
        // Suppression de la companie
        $updatedCompanie = $companiesRepository->find($webhookData["data"]["id"]);
        $em->remove($updatedCompanie);
        $em->flush();

        $updatedAddresses = $addressesRepository->findAll();

        foreach($updatedAddresses as $index => $subUpdatedAddresses) {
            
            if($subUpdatedAddresses['company_id'] == $webhookData["data"]["id"]) {
                
                $em->remove($subUpdatedAddresses);
            }
        }

        return new Response(' Done!', Response::HTTP_OK);
    }



    #[Route('/webhook/companies/filter', name: 'app_webhook_companies_filter')]
    public function webhookCompaniesFilter(SessionInterface $session, EntityManagerInterface $em, CompaniesRepository $companiesRepository,AddressesRepository $addressesRepository, LoggerInterface $logger): Response 
    {
        $webhookData = $session->get('webhook_data');
        
        if (isset($webhookData['topic']) && $webhookData['topic'] === 'company.created') {

            $this->creatingCompanies($session, $em);
            
        }  
        else if(isset($webhookData['topic']) && $webhookData['topic'] === 'company.updated') {

            $this->updatingCompanies($session, $em, $companiesRepository, $addressesRepository);
            
        }
        else if(isset($webhookData['topic']) && $webhookData['topic'] === 'company.deleted') {
        
            $this->deletingCompanies($session, $em, $companiesRepository, $addressesRepository);
        }

        return new Response(' Done!', Response::HTTP_OK);
    }





    
}


