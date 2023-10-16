<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\QuotationsRepository;
use App\Entity\Quotations;
use App\Repository\CompaniesRepository;

use App\Service\CallApiService;

class QuotationsController extends AbstractController
{
    private $logger;
    private $client;
    private $callApiService;

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client, CallApiService $callApiService) {
        $this->logger = $webhookLogger;
        $this->client = $client;
        $this->callApiService = $callApiService;
    }
    
    #[Route('/webhook/quotations', name: 'app_webhook_quotations', methods: 'POST')]
    public function getWebhookQuotations(Request $request, SessionInterface $session, EntityManagerInterface $em, QuotationsRepository $quotationsRepository, CompaniesRepository $companiesRepository, LoggerInterface $logger): Response {
        $response = json_decode($request->getContent(), true);
    
        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }
    
        $session->set('webhook_data', $response);
        $this->logger->info('Quotation received!', $response);
        $this->webhookQuotationsFilter($session, $em, $quotationsRepository, $companiesRepository, $logger);
    
        return new Response('Received!', Response::HTTP_OK);
    }


    private function createQuotation($webhookData, EntityManagerInterface $em, CompaniesRepository $companiesRepository): void {
        $quotation = new Quotations();
        $this->mapDataToQuotation($quotation, $webhookData, $companiesRepository);
        $em->persist($quotation);
        $em->flush();
    }
    
    private function updateQuotation($webhookData, EntityManagerInterface $em, CompaniesRepository $companiesRepository, QuotationsRepository $quotationsRepository): void {
        $quotation = $quotationsRepository->find($webhookData["data"]["id"]);
        if (!$quotation) {
            throw new \Exception("Quotation with ID " . $webhookData["data"]["id"] . " not found.");
        }
        $this->mapDataToQuotation($quotation, $webhookData, $companiesRepository);
        $em->flush();
    }
    
    private function mapDataToQuotation(Quotations $quotation, $webhookData, CompaniesRepository $companiesRepository): void {
        
        $quotation->setId($webhookData['data']["id"]);
        $quotation->setNumber($webhookData['data']["number"]);
        $quotation->setTitle($webhookData['data']["title"]);

        $creationDate = new \DateTime($webhookData['data']["date"]);
        if($creationDate !== null) {
            $quotation->setDate($creationDate);
        } else {
            $creationDate = null;
            $quotation->setDate($creationDate);
        }
        
        $creationExpiryDate = new \DateTime($webhookData['data']["expiry_date"]);
        if($creationDate !== null) {
            $quotation->setExpiryDate($creationExpiryDate);
        } else {
            $creationExpiryDate = new \DateTime('');
            $quotation->setExpiryDate($creationExpiryDate);
        }

        $creationSentDate = new \DateTime($webhookData['data']["sent_date"]);
        $quotation->setSentDate($creationSentDate);
        $creationLastUpdateDate = new \DateTime($webhookData['data']["last_update_date"]);
        $quotation->setLastUpdateDate($creationLastUpdateDate);
        $quotation->setStatus($webhookData['data']["status"]);
        $companyId = $webhookData['data']["company_id"];
        $companyEntity = $companiesRepository->find($companyId);

        if ($companyEntity) {
            $quotation->setCompany($companyEntity);
        } else {
            throw new \Exception("Compagnie non trouvée avec l'ID: " . $companyId);
        }

        $quotation->setCompanyName($webhookData['data']["company_name"]);
        $quotation->setTotalAmount($webhookData['data']["global_discount_amount"]);
        $quotation->setGlobalDiscountAmount($webhookData['data']["global_discount_amount"]);
        $quotation->setGlobalDiscountWithTax($webhookData['data']["global_discount_amount_with_tax"]);
        $quotation->setGlobalDiscountUnitIsPercent($webhookData['data']["global_discount_unit_is_percent"]);
        $quotation->setGlobalDiscountComments($webhookData['data']["global_discount_comments"]);
        $quotation->setPreTaxAmount($webhookData['data']["pre_tax_amount"]);
        $quotation->setTaxAmount($webhookData['data']["tax_amount"]);
        $quotation->setTotalAmount($webhookData['data']["total_amount"]);
        $quotation->setMargin($webhookData['data']["margin"]);
        $quotation->setPaymentsToDisplayInPdf($webhookData['data']["payments_to_display_in_pdf"]);

        if (isset($webhookData['data']["electronic_signature_date"]["date"])) {
            $creationSignatureDate = new \DateTime($webhookData['data']["electronic_signature_date"]["date"]);
        } else {
            $creationSignatureDate = null;
        }
        $quotation->setSignatureDate($creationSignatureDate);
    
        $companyEntity = $companiesRepository->find($webhookData["data"]["company_id"]);
        if (!$companyEntity) {
            throw new \Exception("Company not found with ID " . $webhookData["data"]["company_id"]);
        }
        $quotation->setCompany($companyEntity);
    }
    
    public function webhookQuotationsFilter(SessionInterface $session, EntityManagerInterface $em, QuotationsRepository $quotationsRepository, CompaniesRepository $companiesRepository, $logger): Response {
        $webhookData = $session->get('webhook_data');
    
        switch ($webhookData['topic']) {
            case 'quotation.created':
                $this->createQuotation($webhookData, $em, $companiesRepository);
                break;
            case 'quotation.updated':
                $this->updateQuotation($webhookData, $em, $companiesRepository, $quotationsRepository);
                break;
        }
    
        $this->QuotationToDatabaseCheck($session, $em, $quotationsRepository, $logger, $companiesRepository);
    
        return new Response('Done!', Response::HTTP_OK);
    }
    


    // VERIFICATION ET AJOUT

    public function QuotationToDatabaseCheck(SessionInterface $session, EntityManagerInterface $em, QuotationsRepository $quotationsRepository, LoggerInterface $logger, $companiesRepository): Response 
    {
        $webhookData = $session->get('webhook_data');
        $this->logger->INFO('checkup: ', $webhookData);
    
        $data = $this->fetchQuotationData();
    
        foreach ($data as $quotation) {
            if ($this->isValidQuotation($quotation, $quotationsRepository)) {
                $dataQuotations = $this->mapToQuotationEntity($quotation, $companiesRepository);
                $em->persist($dataQuotations);
            }
        }
    
        $this->saveEntities($em);
    
        return new Response('Done!', Response::HTTP_OK);
    }
    
    private function fetchQuotationData(): array {
        $response = $this->client->request('GET', 'https://axonaut.com/api/v2/quotations', [
            'headers' => [
                'userApiKey' => $_ENV['API_KEY'],
            ]
        ]);
    
        return $response->toArray();
    }
    
    private function isValidQuotation(array $quotation, QuotationsRepository $quotationsRepository): bool {
        return is_null($quotationsRepository->find($quotation["id"])) && $quotation['company_name'] !== null;
    }
    
    private function mapToQuotationEntity(array $quotation, $companiesRepository): Quotations {

    
        $this->logger->INFO('DATA NULL: ', $quotation);

        $dataQuotations = new Quotations();

        $dataQuotations->setId($quotation["id"]);
        $dataQuotations->setNumber($quotation["number"]);
        $dataQuotations->setTitle($quotation["title"]);

        $creationDate = new \DateTime($quotation["date"]);
        if($creationDate !== null) {
            $dataQuotations->setDate($creationDate);
        } else {
            $creationDate = null;
            $dataQuotations->setDate($creationDate);
        }
        
        $creationExpiryDate = new \DateTime($quotation["expiry_date"]);
        if($creationDate !== null) {
            $dataQuotations->setExpiryDate($creationExpiryDate);
        } else {
            $creationExpiryDate = new \DateTime('');
            $dataQuotations->setExpiryDate($creationExpiryDate);
        }

        $creationSentDate = new \DateTime($quotation["sent_date"]);
        $dataQuotations->setSentDate($creationSentDate);
        $creationLastUpdateDate = new \DateTime($quotation["last_update_date"]);
        $dataQuotations->setLastUpdateDate($creationLastUpdateDate);
        $dataQuotations->setStatus($quotation["status"]);
        $companyId = $quotation["company_id"];
        $companyEntity = $companiesRepository->find($companyId);

        if ($companyEntity) {
            $dataQuotations->setCompany($companyEntity);
        } else {
            throw new \Exception("Compagnie non trouvée avec l'ID: " . $companyId);
        }

        $dataQuotations->setCompanyName($quotation["company_name"]);
        $dataQuotations->setTotalAmount($quotation["global_discount_amount"]);
        $dataQuotations->setGlobalDiscountAmount($quotation["global_discount_amount"]);
        $dataQuotations->setGlobalDiscountWithTax($quotation["global_discount_amount_with_tax"]);
        $dataQuotations->setGlobalDiscountUnitIsPercent($quotation["global_discount_unit_is_percent"]);
        $dataQuotations->setGlobalDiscountComments($quotation["global_discount_comments"]);
        $dataQuotations->setPreTaxAmount($quotation["pre_tax_amount"]);
        $dataQuotations->setTaxAmount($quotation["tax_amount"]);
        $dataQuotations->setTotalAmount($quotation["total_amount"]);
        $dataQuotations->setMargin($quotation["margin"]);
        $dataQuotations->setPaymentsToDisplayInPdf($quotation["payments_to_display_in_pdf"]);

        if (isset($quotation["electronic_signature_date"]["date"])) {
            $creationSignatureDate = new \DateTime($quotation["electronic_signature_date"]["date"]);
        } else {
            $creationSignatureDate = null;
        }
        $dataQuotations->setSignatureDate($creationSignatureDate);
        
        return $dataQuotations;
    }
    
    private function saveEntities(EntityManagerInterface $em): void {
        try {
            $em->flush();
        } catch (\Exception $e) {
            $this->logger->error('Data saving error: ', ['error' => $e->getMessage()]);
        }
    }

}
