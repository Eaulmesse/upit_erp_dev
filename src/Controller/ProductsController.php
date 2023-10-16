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
use App\Repository\ProductsRepository;
use App\Entity\Products;

class ProductsController extends AbstractController
{
    private $logger;
    private $client;

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
    }

    // #[Route('/webhook/products', name: 'app_webhook_products', methods: 'POST')]
    // public function getWebhookProducts(Request $request, SessionInterface $session, EntityManagerInterface $em, ProductsRepository $productsRepository, LoggerInterface $logger): Response
    // {
    //     $response = json_decode($request->getContent(), true);

    //     if ($response === null) {
    //         $this->logger->error('Invalid JSON received.');
    //         return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
    //     }
        
    //     // Traitez vos données ici (par exemple, stockez-les dans une base de données, etc.)


    //     $session->set('webhook_data', $response);
        

    //     // Mise en log webhook.log
    //     $this->logger->info('Webhook products received!', $response);



    //     $this->webhookProductsFilter($session, $em, $productsRepository, $logger);


    //     return new Response('Received!', Response::HTTP_OK);
    // }

    // public function creatingProducts(SessionInterface $session, EntityManagerInterface $em): Response
    // {

    //     $webhookData = $session->get('webhook_data');

    //     $this->logger->INFO('Création: ', $webhookData);

            
    //     // Création du nouvel objet Companies
    //     $dataProducts = new Products();

    //     $dataProducts->setId($webhookData["data"]["id"]);
    //     $dataProducts->setName($webhookData["data"]["name"]);
    //     $dataProducts->setProductCode($webhookData["data"]["product_code"]);
    //     $dataProducts->setSupplierProductCode(($webhookData["data"]["supplier_product_code"]));
    //     $dataProducts->setDescription($webhookData["data"]["description"]);
    //     $dataProducts->setPrice($webhookData["data"]["price"]);
    //     $dataProducts->setPriceWithTax($webhookData["data"]["price_with_tax"]);
    //     $dataProducts->setTaxRate($webhookData["data"]["tax_rate"]);
    //     $dataProducts->setType($webhookData["data"]["type"]);
    //     $dataProducts->setCategory($webhookData["data"]["category"]);
    //     $dataProducts->setJobCosting(floatval($webhookData["data"]["job_costing"]));
    //     $dataProducts->setStock(intval($webhookData["data"]["stock"]));
    //     $dataProducts->setLocation($webhookData["data"]["location"]);
    //     $dataProducts->setUnit($webhookData["data"]["unit"]);
    //     $dataProducts->setDisabled($webhookData["data"]["disabled"]);
    //     $dataProducts->setInternalId(($webhookData["data"]["internal_id"]));
    //     $dataProducts->setWeightedAverageCost(floatval($webhookData["data"]["weighted_average_cost"]));
    //     $dataProducts->setImage(intval($webhookData["data"]["image"]));

    //     $em->persist($dataProducts);

    //     try{
    //         $em->flush();
    //     }
    //     catch(\Exception $e){
    //         error_log($e->getMessage());
    //     }


    //     return new Response(' Done!', Response::HTTP_OK);
    // }

    // public function updatingProducts(SessionInterface $session, EntityManagerInterface $em,  ProductsRepository $productsRepository): Response
    // {
    //     $webhookData = $session->get('webhook_data');
    //     $this->logger->INFO('Modification: ', $webhookData);

    //     $updatedProduct = $productsRepository->find($webhookData["data"]["id"]);

    //     $em->remove($updatedProduct);
    //     $em->flush();

    //     $dataProducts = new Products();

    //     $dataProducts->setId($webhookData["data"]["id"]);
    //     $dataProducts->setName($webhookData["data"]["name"]);
    //     $dataProducts->setProductCode($webhookData["data"]["product_code"]);
    //     $dataProducts->setSupplierProductCode(($webhookData["data"]["supplier_product_code"]));
    //     $dataProducts->setDescription($webhookData["data"]["description"]);
    //     $dataProducts->setPrice($webhookData["data"]["price"]);
    //     $dataProducts->setPriceWithTax($webhookData["data"]["price_with_tax"]);
    //     $dataProducts->setTaxRate($webhookData["data"]["tax_rate"]);
    //     $dataProducts->setType($webhookData["data"]["type"]);
    //     $dataProducts->setCategory($webhookData["data"]["category"]);
    //     $dataProducts->setJobCosting(floatval($webhookData["data"]["job_costing"]));
    //     $dataProducts->setStock(intval($webhookData["data"]["stock"]));
    //     $dataProducts->setLocation($webhookData["data"]["location"]);
    //     $dataProducts->setUnit($webhookData["data"]["unit"]);
    //     $dataProducts->setDisabled($webhookData["data"]["disabled"]);
    //     $dataProducts->setInternalId(($webhookData["data"]["internal_id"]));
    //     $dataProducts->setWeightedAverageCost(floatval($webhookData["data"]["weighted_average_cost"]));
    //     $dataProducts->setImage(intval($webhookData["data"]["image"]));

    //     $em->persist($dataProducts);

    //     try{
    //         $em->flush();
    //     }
    //     catch(\Exception $e){
    //         error_log($e->getMessage());
    //     }

    //     return new Response(' Done!', Response::HTTP_OK);
    // }

    // public function deletingProducts(SessionInterface $session, EntityManagerInterface $em,  ProductsRepository $productsRepository): Response
    // {
    //     $webhookData = $session->get('webhook_data');

    //     $this->logger->INFO('Suppression: ', $webhookData);
        
    //     // Suppression de la companie
    //     $updatedProduct = $productsRepository->find($webhookData["data"]["id"]);
    //     $em->remove($updatedProduct);
    //     $em->flush();

    //     return new Response(' Done!', Response::HTTP_OK);
    // }

    // public function webhookProductsFilter(SessionInterface $session, EntityManagerInterface $em, ProductsRepository $productsRepository, LoggerInterface $logger): Response 
    // {
    //     $webhookData = $session->get('webhook_data');
        
    //     if (isset($webhookData['topic']) && $webhookData['topic'] === 'product.created') {

    //         $this->creatingProducts($session, $em);
            
    //     }  
    //     else if(isset($webhookData['topic']) && $webhookData['topic'] === 'product.updated') {

    //         $this->updatingProducts($session, $em, $productsRepository);
            
    //     }
    //     else if(isset($webhookData['topic']) && $webhookData['topic'] === 'product.deleted') {
        
    //         $this->deletingProducts($session, $em, $productsRepository);
    //     }

    //     return new Response(' Done!', Response::HTTP_OK);
    // }
}
