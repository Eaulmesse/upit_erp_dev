<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\WorkforcesRepository;
use App\Entity\Workforces;

class WorkforcesController extends AbstractController
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $webhookLogger;
    }

    #[Route('/workforces/get', name: 'app_get_workforces')]
    public function callAPI(Request $request, SessionInterface $session, EntityManagerInterface $em, WorkforcesRepository $workforcesRepository, LoggerInterface $logger): Response
    {

        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/workforces',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                    // Ajoutez d'autres en-têtes si nécessaire
                ]
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);
        // dd($data);
        $this->dataCheck($session, $em, $workforcesRepository, $logger);

        return new Response('Received!', Response::HTTP_OK);
    }
    
    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, WorkforcesRepository $workforcesRepository, LoggerInterface $logger): Response
    {   
        $data = $session->get('api_data');

        foreach($data as $workforcesData) {

            $inDatabaseId = $workforcesRepository->find($workforcesData['id']);

            if ($inDatabaseId == null) {
                
                $inDatabaseId = $this->workforcesToDatabase($workforcesData, $em);
                $em->persist($inDatabaseId);
            }   
            else {
                $inDatabaseId = $this->workforcesToDatabase($workforcesData, $em);
            } 
        }

        $workforcesIdsInData = array_map(function($workforcesData) {
            return $workforcesData['id'];
        }, $data);

        $allWorkforces = $workforcesRepository->findall();
        foreach ($allWorkforces as $workforce) {
            if (!in_array($workforce->getId(), $workforcesIdsInData)) {
                $em->remove($workforce);
            }
        }

        $this->saveWorkforces($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    

    private function workforcesToDatabase($workforcesData, EntityManagerInterface $em, ?Workforces $workforce = null): Workforces
    {
        $workforce = New Workforces;


        $workforce->setId($workforcesData['id']);
        $workforce->setEmail($workforcesData['email']);
        $workforce->setGender($workforcesData['gender']);
        $workforce->setFirstname($workforcesData['firstname']);
        $workforce->setLastname($workforcesData['lastname']);
        $workforce->setAddressStreet($workforcesData['address_street']);
        $workforce->setAddressZipCode($workforcesData['address_zip_code']);
        $workforce->setAddressCity($workforcesData['address_city']);
        $workforce->setJob($workforcesData['job']);
        $workforce->setPhone($workforcesData['phone']);

        if (!empty($workforcesData['birth'])) {
            $birthDate = new \DateTime($workforcesData['birth']);
            $workforce->setBirth($birthDate);
        } else {
            $workforce->setBirth(null);
        }

        if (!empty($workforcesData['entry_date'])) {
            $entryDate = new \DateTime($workforcesData['entry_date']);
            $workforce->setEntryDate($entryDate);
        } else {
            $workforce->setEntryDate(null);
        }

        if (!empty($workforcesData['exit_date'])) {
            $exitDate = new \DateTime($workforcesData['exit_date']);
            $workforce->setExitDate($exitDate);
        } else {
            $workforce->setExitDate(null);
        }

        $workforce->setThirdpartyCode($workforcesData['thirdparty_code']);


        return $workforce;
    }

    private function saveWorkforces(EntityManagerInterface $em): void
    {
        $em->flush();
    }


    
}
