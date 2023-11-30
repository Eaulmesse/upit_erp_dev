<?php

namespace App\Service;

use App\Entity\Quotations;
use Psr\Log\LoggerInterface;
use App\Repository\UsersRepository;
use App\Repository\ProductsRepository;
use App\Repository\ProjectsRepository;
use App\Repository\CompaniesRepository;
use App\Repository\ContractsRepository;
use App\Repository\QuotationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\QuotationLinesApiService;
use App\Repository\OpportunitiesRepository;
use App\Repository\QuotationLinesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class QuotationsApiService 
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, UsersRepository $usersRepository, CompaniesRepository $companiesRepository, ProjectsRepository $projectsRepository, OpportunitiesRepository $opportunitiesRepository, ContractsRepository $contractsRepository, QuotationsRepository $quotationsRepository, QuotationLinesRepository $quotationLinesRepository,  QuotationLinesApiService $quotationLinesApiService, ProductsRepository $productsRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/quotations', // Remplacement de invoices par quotations
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $usersRepository, $companiesRepository, $projectsRepository, $opportunitiesRepository, $contractsRepository, $quotationsRepository, $quotationLinesRepository, $quotationLinesApiService, $productsRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, UsersRepository $usersRepository, CompaniesRepository $companiesRepository, ProjectsRepository $projectsRepository, OpportunitiesRepository $opportunitiesRepository, ContractsRepository $contractsRepository, QuotationsRepository $quotationsRepository, QuotationLinesRepository $quotationLinesRepository,  QuotationLinesApiService $quotationLinesApiService, ProductsRepository $productsRepository): Response
    {
        $data = $session->get('api_data');

        foreach ($data as $quotationsData) {
            $quotationLinesApiService->getData($session, $em, $quotationsData,  $quotationLinesRepository, $quotationsRepository, $productsRepository);
            $em->persist($this->quotationsToDatabase($quotationsData, $em, $usersRepository, $companiesRepository, $projectsRepository, $opportunitiesRepository, $contractsRepository));
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $quotationsIdsInData = array_map(function ($quotationsData) {
            return $quotationsData['id'];
        }, $data);

        $allQuotations = $quotationsRepository->findAll();
        foreach ($allQuotations as $quotations) {
            if (!in_array($quotations->getId(), $quotationsIdsInData)) {
                $em->remove($quotations);
            }
        }

        $this->saveQuotations($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function quotationsToDatabase($quotationsData, EntityManagerInterface $em, UsersRepository $usersRepository, CompaniesRepository $companiesRepository, ProjectsRepository $projectsRepository, OpportunitiesRepository $opportunitiesRepository, ContractsRepository $contractsRepository ,?Quotations $quotations = null): Quotations
    {
        $quotationsId = $quotationsData['id'];
        $quotations = $em->getRepository(Quotations::class)->find($quotationsId);

        if ($quotations === null) {
            $quotations = new Quotations();
            $quotations->setId($quotationsId);
        }
        // dd($quotationsData);
        $quotations->setNumber($quotationsData["number"]);
        $quotations->setTitle($quotationsData["title"]);
        $quotations->setDate(new \DateTime($quotationsData["date"]));
        $quotations->setExpiryDate(new \DateTime($quotationsData["expiry_date"]));
        $quotations->setSentDate(new \DateTime($quotationsData["sent_date"]));
        $quotations->setLastUpdateDate(new \DateTime($quotationsData["last_update_date"]));
        $quotations->setStatus($quotationsData["status"]);
        $quotations->setDateCustomerAnswer(new \DateTime($quotationsData["date_customer_answer"]));
        $quotations->setUser($usersRepository->find($quotationsData['user_id']));
        $quotations->setCompany($companiesRepository->find($quotationsData['company_id']));
        $quotations->setCompanyName($quotationsData["company_name"]);

        if ($quotationsData['project_id'] !== null) {
            $project = $projectsRepository->find($quotationsData['project_id']);

            if ($project !== null) {
                $quotations->setProject($project);
            } 
        } else {
            $quotations->setProject(null);
        }

        if ($quotationsData['opportunity_id'] !== null) {
            $opportunities = $opportunitiesRepository->find($quotationsData['opportunity_id']);

            if ($opportunities !== null) {
                $quotations->setOpportunitiy($opportunities);
            } 
        } else {
            $quotations->setOpportunitiy(null);
        }

        if ($quotationsData['contract_id'] !== null) {
            $contracts = $contractsRepository->find($quotationsData['contract_id']);

            if ($contracts !== null) {
                $quotations->setContract($contracts);
            } 
        } else {
            $quotations->setContract(null);
        }

        
        // $quotations->setContract($contractsRepository->find($quotationsData['contract_id']));
        $quotations->setGlobalDiscountAmount($quotationsData["global_discount_amount"]);
        $quotations->setGlobalDiscountWithTax($quotationsData["global_discount_amount_with_tax"]);
        $quotations->setGlobalDiscountUnitIsPercent($quotationsData["global_discount_unit_is_percent"]);
        $quotations->setGlobalDiscountComments($quotationsData["global_discount_comments"]);
        $quotations->setPreTaxAmount($quotationsData["pre_tax_amount"]);
        $quotations->setTaxAmount($quotationsData["tax_amount"]);
        $quotations->setTotalAmount($quotationsData["total_amount"]);
        $quotations->setMargin($quotationsData["margin"]);
        $quotations->setPaymentsToDisplayInPdf($quotationsData["payments_to_display_in_pdf"]);

        if (isset($quotationsData["electronic_signature_date"]) && $quotationsData["electronic_signature_date"]["date"] != null) {
            $quotations->setSignatureDate(new \DateTime($quotationsData["electronic_signature_date"]["date"]));
        }
        
        
        $quotations->setComments($quotationsData["comments"]);
        $quotations->setPublicPath($quotationsData["public_path"]);
        $quotations->setCustomerPortalUrl($quotationsData["customer_portal_url"]);

        return $quotations;
    }

    private function saveQuotations(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
