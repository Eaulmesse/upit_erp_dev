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
use App\Repository\CompaniesRepository;

class WebhookController extends AbstractController
{

    private $logger;


    public function __construct(LoggerInterface $webhookLogger)
    {
        $this->logger = $webhookLogger;
    }
    
    #[Route('/webhook/companies', name: 'app_webhook_companies', methods: 'POST')]
    public function getWebhookCompanies(Request $request, SessionInterface $session, EntityManagerInterface $em, CompaniesRepository $companiesRepository, LoggerInterface $logger): Response
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



        $this->webhookCompaniesFilter($session, $em, $companiesRepository, $logger);


        return new Response('Received!', Response::HTTP_OK);
    }

    #[Route('/webhook/companies/filter', name: 'app_webhook_companies_filter')]
    public function webhookCompaniesFilter(SessionInterface $session, EntityManagerInterface $em, CompaniesRepository $companiesRepository, LoggerInterface $logger): Response 
    {
        $webhookData = $session->get('webhook_data');
        
        if (isset($webhookData['topic']) && $webhookData['topic'] === 'company.created') {
            $this->logger->INFO('Création: ', $webhookData);


            // Création du nouveau
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
           
                
            try{
                $em->flush();
            }
            catch(\Exception $e){
                error_log($e->getMessage());
            }
            
            
            

        }  
        else if(isset($webhookData['topic']) && $webhookData['topic'] === 'company.updated') {


            $this->logger->INFO('Modification: ', $webhookData);
            // Suppression de la companie
            $updatedCompanie = $companiesRepository->find($webhookData["data"]["id"]);
            $em->remove($updatedCompanie);
            $em->flush();

            // Création du nouveau
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

            
        }
        else if(isset($webhookData['topic']) && $webhookData['topic'] === 'company.deleted') {
        
            $this->logger->INFO('Suppression: ', $webhookData);
            // Suppression de la companie
            $updatedCompanie = $companiesRepository->find($webhookData["data"]["id"]);
            $em->remove($updatedCompanie);
            $em->flush();

        }

        
        
        

        return new Response(' Done!', Response::HTTP_OK);
    }
}


