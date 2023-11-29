<?php

namespace App\Service;

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

class CompaniesWebhookService 
{

    private $logger;
    private $client;


    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
    }

    public function getWebhookCompanies(Request $request, SessionInterface $session, EntityManagerInterface $em, CompaniesRepository $companiesRepository, AddressesRepository $addressesRepository): Response
    {
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }
        
        $session->set('webhook_data', $response);
        $this->logger->info('Webhook received!', $response);

        $this->webhookCompaniesFilter($session, $em, $addressesRepository, $companiesRepository);

        // return $this->forward('App\Controller\AddressesController::GetWebhookFromCompanies', [
        //     'responseData' => $response,
        // ]);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function createCompany($webhookData, EntityManagerInterface $em, CompaniesRepository $companiesRepository): void {

        $this->logger->info('Creation company', $webhookData);
        $company = new Companies();
        $this->mapDataToCompanies($company, $webhookData, $companiesRepository);
        $em->persist($company);
        $em->flush();
    }

    private function updateCompany($webhookData, EntityManagerInterface $em, CompaniesRepository $companiesRepository, AddressesRepository $addressesRepository): void {

        $this->logger->info('Update company', $webhookData);
        $company = $companiesRepository->find($webhookData["data"]["id"]);
        if (!$company) {
            throw new \Exception("Quotation with ID " . $webhookData["data"]["id"] . " not found.");
        }
        $this->mapDataToCompanies($company, $webhookData, $companiesRepository);
        $em->flush();
    }

    private function deleteCompany($webhookData, EntityManagerInterface $em, CompaniesRepository $companiesRepository, AddressesRepository $addressesRepository): void {

        $this->logger->INFO('Suppression: ', $webhookData);
        
        $company = $companiesRepository->find($webhookData["data"]["id"]);
        $em->remove($company);
        $em->flush();
    }

    private function mapDataToCompanies(Companies $company, $webhookData, CompaniesRepository $companiesRepository): void {

        $company->setId($webhookData["data"]["id"]);
        $company->setName($webhookData["data"]["name"]);

        $creationDate = new \DateTime($webhookData["data"]["creation_date"]);
        $company->setCreationDate($creationDate);

        $company->setAddressStreet($webhookData["data"]["address_street"]);
        $company->setAddressZipCode(intval($webhookData["data"]["address_zip_code"]));
        $company->setAddressCity($webhookData["data"]["address_city"]);
        $company->setAddressRegion($webhookData["data"]["address_region"]);
        $company->setAddressCountry($webhookData["data"]["address_country"]);
        $company->setComments($webhookData["data"]["comments"]);
        $company->setIsSupplier($webhookData["data"]["is_supplier"]);
        $company->setIsProspect($webhookData["data"]["is_prospect"]);
        $company->setIsCustomer($webhookData["data"]["is_customer"]);
        $company->setCurrency($webhookData["data"]["currency"]);
        $company->setLanguage($webhookData["data"]["language"]);
        $company->setThirdpartyCode($webhookData["data"]["thirdparty_code"]);
        $company->setIntracommunityNumber(intval($webhookData["data"]["intracommunity_number"]));
        $company->setSupplierThidpartyCode($webhookData["data"]["supplier_thirdparty_code"]);
        $company->setSiret(intval($webhookData["data"]["siret"]));
        $company->setIsB2C(boolval($webhookData["data"]["isB2C"]));
    
    }

    public function webhookCompaniesFilter(SessionInterface $session, EntityManagerInterface $em, AddressesRepository $addressesRepository, CompaniesRepository $companiesRepository): Response {
        $webhookData = $session->get('webhook_data');
    
        switch ($webhookData['topic']) {
            case 'company.created':
                $this->createCompany($webhookData, $em, $companiesRepository);
                break;
            case 'company.updated':
                $this->updateCompany($webhookData, $em, $companiesRepository, $addressesRepository);
                break;
            case 'company.deleted':
                $this->deleteCompany($webhookData, $em, $companiesRepository, $addressesRepository);
                break;
        }
    
        return new Response('Done!', Response::HTTP_OK);
    }
}





















