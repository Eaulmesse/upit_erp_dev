<?php

namespace App\Controller;

use App\Service\ApiService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiController extends AbstractController
{   
    #[Route('/api/get/{object}', name: 'app_api_get')]
    public function processApiRequest(ApiService $apiService, EntityManagerInterface $em, string $object): Response
    {
        $className = str_replace('-', ' ', $object); // Retire le tiret
        $className= ucwords($className); // Met en majuscules les premières lettres des mots
        $className = str_replace(' ', '', $className); // Retire les espaces
        $className = ucfirst($className); // Met la première lettre en majuscule

        $repository = $em->getRepository(trim('App\Entity\ ') . ucfirst($className));
        
        // Configuration spécifique à l'appel d'API "projectNatures"
        $ApiConfig = [
            'url' => 'https://axonaut.com/api/v2/' . $object,
        ];

        // dd($apiService->fetchData($ApiConfig));
        // Récupérer les données depuis l'API "projectNatures"
        $data = $apiService->fetchData($ApiConfig);
        

        

        // Gérer les doublons et créer/enregistrer les données
        $apiService->dataCheck($data, $className, $object, $repository, $em);
        $apiService->saveEntities($em);

        
        return $this->json($data);
    }
}
