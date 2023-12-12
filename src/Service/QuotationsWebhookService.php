<?php

namespace App\Service;

use App\Entity\Quotations;
use Psr\Log\LoggerInterface;
use App\Repository\UsersRepository;
use App\Repository\ProjectsRepository;
use App\Repository\CompaniesRepository;
use App\Repository\ContractsRepository;
use App\Repository\QuotationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OpportunitiesRepository;
use App\Repository\ProductsRepository;
use App\Repository\QuotationLinesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuotationsWebhookService 
{

    private $logger;
    private $client;


    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
    }

    public function getWebhookQuotations(Request $request, SessionInterface $session,  LoggerInterface $logger, EntityManagerInterface $em, UsersRepository $usersRepository, CompaniesRepository $companiesRepository, ProjectsRepository $projectsRepository, ContractsRepository $contractsRepository, OpportunitiesRepository $opportunitiesRepository, QuotationsRepository $quotationsRepository,QuotationLinesApiService $quotationLinesApiService, QuotationLinesRepository $quotationLinesRepository, ProductsRepository $productsRepository): Response
    {
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }

        $session->set('webhook_data', $response);
        $this->logger->info('Webhook received!', $response);

        $this->webhookQuotationsFilter($session, $em, $usersRepository, $companiesRepository, $projectsRepository, $contractsRepository, $opportunitiesRepository, $quotationsRepository, $quotationLinesApiService, $quotationLinesRepository, $productsRepository);

        
        return new Response('Done!', Response::HTTP_OK);
    }


    private function createQuotations($webhookData, SessionInterface $session, EntityManagerInterface $em, UsersRepository $usersRepository, CompaniesRepository $companiesRepository, ProjectsRepository $projectsRepository, ContractsRepository $contractsRepository, OpportunitiesRepository $opportunitiesRepository, QuotationLinesApiService $quotationLinesApiService, QuotationLinesRepository $quotationLinesRepository, ProductsRepository $productsRepository, QuotationsRepository $quotationsRepository): void {

        $this->logger->info('Creation quotations', $webhookData);
        $quotations = new Quotations();
        $quotationsData = $webhookData['data'];

        $this->mapDataToquotations($quotations, $webhookData, $usersRepository, $companiesRepository, $projectsRepository, $contractsRepository, $opportunitiesRepository);
        $em->persist($quotations);
        $em->flush();
        
        $quotationLinesApiService->getData($session, $em, $quotationsData,  $quotationLinesRepository, $quotationsRepository, $productsRepository);
    }

    private function updateQuotations($webhookData, EntityManagerInterface $em, QuotationsRepository $quotationsRepository): void {

        $this->logger->info('Update quotations', $webhookData);
        $quotations = $quotationsRepository->find($webhookData["data"]["id"]);
        if (!$quotations) {
            throw new \Exception("Quotation with ID " . $webhookData["data"]["id"] . " not found.");
        }
        $quotations->setStatus($webhookData["data"]["status"]);
        $em->flush();
    }

    private function mapDataToQuotations(Quotations $quotations, $webhookData, UsersRepository $usersRepository, CompaniesRepository $companiesRepository, ProjectsRepository $projectsRepository, ContractsRepository $contractsRepository, OpportunitiesRepository $opportunitiesRepository): void {
        
        $quotations->setId($webhookData['data']["id"]);
        $quotations->setNumber($webhookData['data']["number"]);
        $quotations->setTitle($webhookData['data']["title"]);
        $quotations->setDate(new \DateTime($webhookData['data']["date"]));
        $quotations->setExpiryDate(new \DateTime($webhookData['data']["expiry_date"]));
        $quotations->setSentDate(new \DateTime($webhookData['data']["sent_date"]));
        $quotations->setLastUpdateDate(new \DateTime($webhookData['data']["last_update_date"]));
        $quotations->setStatus($webhookData['data']["status"]);

        
        $quotations->setUser($usersRepository->find($webhookData['data']['user_id']));
        $quotations->setCompany($companiesRepository->find($webhookData['data']['company_id']));
        $quotations->setCompanyName($webhookData['data']["company_name"]);

        if ($webhookData['data']['project_id'] !== null) {
            $project = $projectsRepository->find($webhookData['data']['project_id']);

            if ($project !== null) {
                $quotations->setProject($project);
            } 
        } else {
            $quotations->setProject(null);
        }

        if ($webhookData['data']['opportunity_id'] !== null) {
            $opportunities = $opportunitiesRepository->find($webhookData['data']['opportunity_id']);

            if ($opportunities !== null) {
                $quotations->setOpportunitiy($opportunities);
            } 
        } else {
            $quotations->setOpportunitiy(null);
        }

        if ($webhookData['data']['contract_id'] !== null) {
            $contracts = $contractsRepository->find($webhookData['data']['contract_id']);

            if ($contracts !== null) {
                $quotations->setContract($contracts);
            } 
        } else {
            $quotations->setContract(null);
        }

        if($webhookData['data']['contract_id'] != null) {
            $quotations->setContract($contractsRepository->find($webhookData['data']['contract_id']));
        } else {
            $quotations->setContract(null);
        }
        
        $quotations->setGlobalDiscountAmount($webhookData['data']["global_discount_amount"]);
        $quotations->setGlobalDiscountWithTax($webhookData['data']["global_discount_amount_with_tax"]);
        $quotations->setGlobalDiscountUnitIsPercent($webhookData['data']["global_discount_unit_is_percent"]);
        $quotations->setGlobalDiscountComments($webhookData['data']["global_discount_comments"]);
        $quotations->setPreTaxAmount($webhookData['data']["pre_tax_amount"]);
        $quotations->setTaxAmount($webhookData['data']["tax_amount"]);
        $quotations->setTotalAmount($webhookData['data']["total_amount"]);
        $quotations->setMargin($webhookData['data']["margin"]);
        $quotations->setPaymentsToDisplayInPdf($webhookData['data']["payments_to_display_in_pdf"]);

        if (isset($webhookData['data']["electronic_signature_date"]) && $webhookData['data']["electronic_signature_date"]["date"] != null) {
            $quotations->setSignatureDate(new \DateTime($webhookData['data']["electronic_signature_date"]["date"]));
        }
        
        
        $quotations->setComments($webhookData['data']["comments"]);
        $quotations->setPublicPath($webhookData['data']["public_path"]);
        $quotations->setCustomerPortalUrl($webhookData['data']["customer_portal_url"]);
    
    }

    public function webhookQuotationsFilter(SessionInterface $session, EntityManagerInterface $em, UsersRepository $usersRepository, CompaniesRepository $companiesRepository, ProjectsRepository $projectsRepository, ContractsRepository $contractsRepository, OpportunitiesRepository $opportunitiesRepository, QuotationsRepository $quotationsRepository, QuotationLinesApiService $quotationLinesApiService, QuotationLinesRepository $quotationLinesRepository, ProductsRepository $productsRepository): Response {
        $webhookData = $session->get('webhook_data');
    
        switch ($webhookData['topic']) {
            case 'quotation.created':
                $this->createQuotations($webhookData, $session, $em, $usersRepository, $companiesRepository, $projectsRepository, $contractsRepository, $opportunitiesRepository,$quotationLinesApiService, $quotationLinesRepository, $productsRepository, $quotationsRepository);
                break;
            case 'quotation.updated':
                $this->updateQuotations($webhookData, $em, $quotationsRepository);
                break;
            
        }
    
        return new Response('Done!', Response::HTTP_OK);
    }
}