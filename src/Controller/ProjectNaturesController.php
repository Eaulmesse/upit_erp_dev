<?php

namespace App\Controller;

use App\Entity\ProjectNatures;
use App\Repository\ProjectNaturesRepository;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectNaturesController extends AbstractController
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $webhookLogger;
    }

    #[Route('/projectNatures/get', name: 'app_get_projectNatures')]
    public function callAPI(Request $request, SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ProjectNaturesRepository $projectNaturesRepository): Response
    {
 
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/projectNatures',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                    // Ajoutez d'autres en-têtes si nécessaire
                ]
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);
        //dd($data);
        $this->dataCheck($session, $em, $logger, $projectNaturesRepository);
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ProjectNaturesRepository $projectNaturesRepository): Response
    {   
        $data = $session->get('api_data');

        foreach($data as $projectNaturesData) {

            $inDatabaseId = $projectNaturesRepository->find($projectNaturesData['id']);

            if ($inDatabaseId == null) {
                $inDatabaseId = $this->projectNaturesToDatabase($projectNaturesData, $em);
                $em->persist($inDatabaseId);
            }   
            else {
                $inDatabaseId = $this->projectNaturesToDatabase($projectNaturesData, $em);
            } 
        }

        $projectNaturesIdsInData = array_map(function($projectNaturesData) {
            return $projectNaturesData['id'];
        }, $data);

        $allprojectNatures = $projectNaturesRepository->findall();
        foreach ($allprojectNatures as $projectNature) {
            if (!in_array($projectNature->getId(), $projectNaturesIdsInData)) {
                $em->remove($projectNature);
            }
        }

        $this->saveProjectNatures($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function projectNaturesToDatabase($projectNaturesData, EntityManagerInterface $em, ?ProjectNatures $projectNatures = null): ProjectNatures
    {        
       $projectNatures = new ProjectNatures;

       $projectNatures->setId($projectNaturesData['id']);
       $projectNatures->setIsDisabled($projectNaturesData['isDisabled']);
       $projectNatures->setName($projectNaturesData['name']);

       return $projectNatures;
    }

    private function saveProjectNatures(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
