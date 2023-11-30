<?php

namespace App\Service;

use App\Entity\ProjectNatures;
use App\Repository\ProjectNaturesRepository;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjectNaturesApiService // Remplacement de QuotationsApiService par ProjectNaturesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/projectNatures', 
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $data = $session->get('api_data');

        foreach ($data as $projectNaturesData) {
            $em->persist($this->projectNaturesToDatabase($projectNaturesData, $em));
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $projectNaturesIdsInData = array_map(function ($projectNaturesData) {
            return $projectNaturesData['id'];
        }, $data);

        $allProjectNatures = $em->getRepository(ProjectNatures::class)->findAll();
        foreach ($allProjectNatures as $projectNatures) {
            if (!in_array($projectNatures->getId(), $projectNaturesIdsInData)) {
                $em->remove($projectNatures);
            }
        }

        $this->saveProjectNatures($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function projectNaturesToDatabase($projectNaturesData, EntityManagerInterface $em): ProjectNatures
    {
        $projectNaturesId = $projectNaturesData['id'];
        $projectNatures = $em->getRepository(ProjectNatures::class)->find($projectNaturesId);

        if ($projectNatures === null) {
            $projectNatures = new ProjectNatures();
            $projectNatures->setId($projectNaturesId);
        }

        $projectNatures->setIsDisabled($projectNaturesData['isDisabled']);
        $projectNatures->setName($projectNaturesData['name']);   

        

        return $projectNatures;
    }

    private function saveProjectNatures(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
