<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;
use App\Entity\Addresses;
use App\Repository\ProductsRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use App\Repository\EmployeesRepository;

class ProductsWebhookService 
{

    private $logger;
    private $client;


    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
    }

    public function getWebhookProducts(Request $request, SessionInterface $session,  LoggerInterface $logger, EntityManagerInterface $em, ProductsRepository $productsRepository): Response
{
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }

        $session->set('webhook_data', $response);
        $this->logger->info('Webhook received!', $response);

        $this->webhookProductsFilter($session, $em, $productsRepository);
        
        return new Response('Done!', Response::HTTP_OK);
    }


    private function createCompany($webhookData, EntityManagerInterface $em): void {

        $this->logger->info('Creation product', $webhookData);
        $product = new Products();
        $this->mapDataToOpportunities($product, $webhookData);
        $em->persist($product);
        $em->flush();
    }

    private function updateCompany($webhookData, EntityManagerInterface $em, ProductsRepository $productsRepository): void {

        $this->logger->info('Update product', $webhookData);
        $product = $productsRepository->find($webhookData["data"]["id"]);
        if (!$product) {
            throw new \Exception("Quotation with ID " . $webhookData["data"]["id"] . " not found.");
        }
        $this->mapDataToOpportunities($product, $webhookData);
        $em->flush();
    }

    private function deleteCompany($webhookData, EntityManagerInterface $em, ProductsRepository $productsRepository): void {

        $this->logger->INFO('Suppression: ', $webhookData);
        
        $product= $productsRepository->find($webhookData["data"]["id"]);
        $em->remove($product);
        $em->flush();
    }

    private function mapDataToOpportunities(Products $product, $webhookData): void {

        $product->setId($webhookData["data"]["id"]);
        $product->setName($webhookData["data"]["name"]);
        $product->setProductCode($webhookData["data"]["product_code"]);
        $product->setSupplierProductCode(($webhookData["data"]["supplier_product_code"]));
        $product->setDescription($webhookData["data"]["description"]);
        $product->setPrice($webhookData["data"]["price"]);
        $product->setPriceWithTax($webhookData["data"]["price_with_tax"]);
        $product->setTaxRate($webhookData["data"]["tax_rate"]);
        $product->setType($webhookData["data"]["type"]);
        $product->setCategory($webhookData["data"]["category"]);
        $product->setJobCosting(floatval($webhookData["data"]["job_costing"]));
        $product->setStock(intval($webhookData["data"]["stock"]));
        $product->setLocation($webhookData["data"]["location"]);
        $product->setUnit($webhookData["data"]["unit"]);
        $product->setDisabled($webhookData["data"]["disabled"]);
        $product->setInternalId(($webhookData["data"]["internal_id"]));
        $product->setWeightedAverageCost(floatval($webhookData["data"]["weighted_average_cost"]));
        $product->setImage(intval($webhookData["data"]["image"]));

    }

    public function webhookProductsFilter(SessionInterface $session, EntityManagerInterface $em, ProductsRepository $productsRepository): Response {
        $webhookData = $session->get('webhook_data');
    
        switch ($webhookData['topic']) {
            case 'product.created':
                $this->createCompany($webhookData, $em, $productsRepository);
                break;
            case 'product.updated':
                $this->updateCompany($webhookData, $em, $productsRepository);
                break;
            case 'product.deleted':
                $this->deleteCompany($webhookData, $em, $productsRepository);
                break;
        }
    
        return new Response('Done!', Response::HTTP_OK);
    }
}





















