<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompaniesRepository;
use App\Repository\ProductsRepository;



class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(CallApiService $callApiService, EntityManagerInterface $entityManager, CompaniesRepository $companiesRepository, ProductsRepository $productRepository): Response
    {

        // dd($callApiService->getCompaniesData($entityManager, $companiesRepository));
        dd($callApiService->getProductsData($entityManager, $productRepository));
        
        

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
