<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\QuotationLinesRepository;
use App\Entity\QuotationLines;
use App\Repository\QuotationsRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;
use App\Repository\ProductsRepository;

class QuotationLinesController extends AbstractController
{
    private $logger;
    private $client;
    private $quotationLinesRespository;


    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client, QuotationLinesRepository $quotationLinesRespository)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
        $this->quotationLinesRespository = $quotationLinesRespository;
    }

    public function GetWebhookFromQuotationLines($responseData, QuotationLinesRepository $quotationLinesRespository, LoggerInterface $logger, SessionInterface $session, EntityManagerInterface $em, ProductsRepository $productsRepository, QuotationsRepository $quotationsRepository): Response
    {
        $session->set('ql_data', $responseData);
        $this->QuotationLinesToDatabase($responseData, $quotationLinesRespository, $logger, $session, $em, $productsRepository, $quotationsRepository);
        
        // $this->logger->INFO('In QL: ', $responseData);
        return new Response('Received!', Response::HTTP_OK);
    }

    public function FetchAddressesData($responseData, QuotationLinesRepository $qlRespository, LoggerInterface $logger, SessionInterface $session): array
    {

        // $this->logger->INFO('fetch: ', $responseData);
        $url = 'https://axonaut.com/api/v2/quotations/{quotationId}';
        $finalUrl = str_replace('{quotationId}', $responseData['data']['id'], $url);

        $response = $this->client->request('GET', $finalUrl, [
            'headers' => [
                'userApiKey' => $_ENV['API_KEY'],
            ]
        ]);
        return $response->ToArray();
    }

    public function getQuotationLineFromDb($uniqueKey)
    {
        $dbLine =  $this->quotationLinesRespository->findOneByUniqueKey($uniqueKey);

        return $dbLine;
    }

    public function QuotationLinesToDatabase($responseData, QuotationLinesRepository $quotationLinesRespository, LoggerInterface $logger, SessionInterface $session, EntityManagerInterface $em, ProductsRepository $productsRepository, QuotationsRepository $quotationsRepository): Response {
        
        $dataAPI = $this->FetchAddressesData($responseData, $quotationLinesRespository, $logger, $session, $em);
        $this->logger->INFO('$responseData', $responseData);
        $this->logger->INFO('$responseData', $dataAPI);
        // Suppression des quotations_lines

        $allQuotationLines = $quotationLinesRespository->findBy(array('quotations' => $dataAPI['id']));
        
        $this->logger->INFO('allQuotationLines', $allQuotationLines);
        
        foreach ($allQuotationLines as $quotationLines) {
            $em->remove($quotationLines);
        }

        $this->mapToQlEntity($dataAPI, $productsRepository, $quotationsRepository, $quotationLinesRespository, $em);

        $this->saveEntities($em);

        return new Response('Done', Response::HTTP_OK);
    }

    private function mapToQlEntity($dataAPI, ProductsRepository $productsRepository, QuotationsRepository $quotationsRepository, QuotationLinesRepository $quotationLinesRespository, EntityManagerInterface $em): QuotationLines {
        
        $dataLines = $dataAPI['quotation_lines'];

        
        $this->logger->INFO('MapToEntity ', $dataAPI);
        $this->logger->INFO('DataLines ', $dataLines);

        foreach($dataLines as $lines) {

            $quotationLinesEntity = new QuotationLines();


            $this->logger->INFO('DataAPI ', $dataAPI);
            $quotationLinesEntity->setQuotationsId($quotationsRepository->find($dataAPI['id']));
        
            $quotationLinesEntity->setProducts($productsRepository->find($lines["product_id"]));
            $quotationLinesEntity->setProductInternalId(intval($lines['product_internal_id']));
            $quotationLinesEntity->setProductName($lines['product_name']);
            $quotationLinesEntity->setProductCode($lines['product_code']);
            $quotationLinesEntity->setTitle($lines['title']);
            $quotationLinesEntity->setDetails($lines['details']);
            $quotationLinesEntity->setQuantity($lines['quantity']);
            $quotationLinesEntity->setUnit($lines['unit']);
            $quotationLinesEntity->setPrice($lines['price']);
            $quotationLinesEntity->setTaxRates($lines['tax_rates'][0]['rate']);
            $quotationLinesEntity->setTaxName($lines['tax_rates'][0]['name']);
            $quotationLinesEntity->setLineDiscountAmount($lines['line_discount_amount']);
            $quotationLinesEntity->setLineDiscountAmountWithTax($lines['line_discount_amount_with_tax']);
            $quotationLinesEntity->setLineDiscountUnitIsPercent($lines['line_discount_unit_is_percent']);
            $quotationLinesEntity->setTaxAmount($lines['tax_amount']);
            $quotationLinesEntity->setPreTaxAmount($lines['pre_tax_amount']);
            $quotationLinesEntity->setTotalAmount($lines['total_amount']);
            $quotationLinesEntity->setMargin($lines['margin']);
            $quotationLinesEntity->setUnitJobCosting($lines['unit_job_costing']);
            $quotationLinesEntity->setChapter($lines['chapter']);

            $em->persist($quotationLinesEntity);
        }

        
        


        $this->logger->INFO('Map: ', $dataLines);

       
        
        return $quotationLinesEntity;
    }

    private function saveEntities(EntityManagerInterface $em): void {
        try {
            $em->flush();
        } catch (\Exception $e) {
            $this->logger->error('Data saving error: ', ['error' => $e->getMessage()]);
        }
    }
}


// $dataAPI = $this->FetchAddressesData($responseData, $qlRespository, $logger, $session, $em);

        // $currentQlIds = []; 
        // $compteur = 0;
        // foreach ($dataAPI as $qlData) {

        //     if (is_array($qlData)) {
        //         $this->logger->info('qlData is an array.', $qlData);
        //     } else {
        //         $this->logger->error('qlData is not an array.', ['value' => $qlData]);
        //     }
            
        //     $qlFromDb = $qlRespository->find($qlData);
            
            
        //     // Si l'adresse existe déjà, la mettre à jour
        //     if ($qlFromDb) {
        //         $qlFromDb = $this->mapToQlEntity($dataAPI, $qlFromDb, $productsRepository, $quotationsRepository, $compteur);
        //     } else {
        //         // Sinon, créer une nouvelle entité adresse
        //         $qlFromDb = $this->mapToQlEntity($dataAPI, $qlFromDb, $productsRepository, $quotationsRepository, $compteur);
        //         $em->persist($qlFromDb);
        //     }
    
        //     $currentQlIds[] = $qlFromDb->getId();
            
        // }
        
        // // Suppression des adresses non présentes dans les données fraîches de l'API
        // $allQl = $qlRespository->find($qlData);
        // foreach ($allQl as $ql) {
        //     if (!in_array($ql->getId(), $currentQlIds)) {
        //         $em->remove($ql);
        //     }
        // }
    
        // $this->saveEntities($em);
