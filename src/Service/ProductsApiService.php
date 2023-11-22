<?php 
namespace App\Service;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Products;
use App\Repository\ProductsRepository;


class ProductsApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    
    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ProductsRepository $productsRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/products',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        // dd($data);
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $productsRepository);
        
    
        
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ProductsRepository $productsRepository): Response
    {   
        $data = $session->get('api_data');

        foreach ($data as $productsData) {
            $products = $this->productsToDatabase($productsData, $em, $productsRepository);
            $em->persist($products);
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $productsIdsInData = array_map(function ($productsData) {
            return $productsData['id'];
        }, $data);

        $allProducts = $productsRepository->findAll();
        foreach ($allProducts as $products) {
            if (!in_array($products->getId(), $productsIdsInData)) {
                $em->remove($products);
            }
        }

        $this->saveProducts($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function productsToDatabase($productsData, EntityManagerInterface $em, ProductsRepository $productsRepository, ?Products $products = null): Products
    {

        $productsId = $productsData['id'];
        $products = $em->getRepository(Products::class)->find($productsId);

        if ($products === null) {
            $products = new Products();
            $products->setId($productsId);
        }

        $products->setId($productsData["id"]);
        $products->setName($productsData["name"]);
        $products->setProductCode($productsData["product_code"]);
        $products->setSupplierProductCode(($productsData["supplier_product_code"]));
        $products->setDescription($productsData["description"]);
        $products->setPrice($productsData["price"]);
        $products->setPriceWithTax($productsData["price_with_tax"]);
        $products->setTaxRate($productsData["tax_rate"]);
        $products->setType($productsData["type"]);
        $products->setCategory($productsData["category"]);
        $products->setJobCosting(floatval($productsData["job_costing"]));
        $products->setStock(intval($productsData["stock"]));
        $products->setLocation($productsData["location"]);
        $products->setUnit($productsData["unit"]);
        $products->setDisabled($productsData["disabled"]);
        $products->setInternalId(($productsData["internal_id"]));
        $products->setWeightedAverageCost(floatval($productsData["weighted_average_cost"]));
        $products->setImage(intval($productsData["image"]));
    

       




        return $products;
    }

    private function saveProducts(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}