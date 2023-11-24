<?php 
namespace App\Service;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Workforces;
use App\Repository\WorkforcesRepository;

class WorkforcesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    
    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, WorkforcesRepository $workforcesRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/workforces',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        // dd($data);
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $workforcesRepository);
        
    
        
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, WorkforcesRepository $workforcesRepository): Response
    {   
        $data = $session->get('api_data');

        foreach ($data as $workforcesData) {
            $workforces = $this->workforcesToDatabase($workforcesData, $em);
            $em->persist($workforces);
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $workforcesIdsInData = array_map(function ($workforcesData) {
            return $workforcesData['id'];
        }, $data);

        $allWorkforces = $workforcesRepository->findAll();
        foreach ($allWorkforces as $workforces) {
            if (!in_array($workforces->getId(), $workforcesIdsInData)) {
                $em->remove($workforces);
            }
        }

        $this->saveWorkforces($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function workforcesToDatabase($workforcesData, EntityManagerInterface $em, ?Workforces $workforces = null): Workforces
    {

        $workforcesId = $workforcesData['id'];
        $workforces = $em->getRepository(workforces::class)->find($workforcesId);

        if ($workforces === null) {
            $workforces = new Workforces();
            $workforces->setId($workforcesId);
        }

        
        $workforces->setId($workforcesData['id']);
        $workforces->setEmail($workforcesData['email']);
        $workforces->setGender($workforcesData['gender']);
        $workforces->setFirstname($workforcesData['firstname']);
        $workforces->setLastname($workforcesData['lastname']);
        $workforces->setAddressStreet($workforcesData['address_street']);
        $workforces->setAddressZipCode($workforcesData['address_zip_code']);
        $workforces->setAddressCity($workforcesData['address_city']);
        $workforces->setJob($workforcesData['job']);
        $workforces->setPhone($workforcesData['phone']);

        if (!empty($workforcesData['birth'])) {
            $birthDate = new \DateTime($workforcesData['birth']);
            $workforces->setBirth($birthDate);
        } else {
            $workforces->setBirth(null);
        }

        if (!empty($workforcesData['entry_date'])) {
            $entryDate = new \DateTime($workforcesData['entry_date']);
            $workforces->setEntryDate($entryDate);
        } else {
            $workforces->setEntryDate(null);
        }

        if (!empty($workforcesData['exit_date'])) {
            $exitDate = new \DateTime($workforcesData['exit_date']);
            $workforces->setExitDate($exitDate);
        } else {
            $workforces->setExitDate(null);
        }

        $workforces->setThirdpartyCode($workforcesData['thirdparty_code']);




        return $workforces;
    }

    private function saveWorkforces(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}