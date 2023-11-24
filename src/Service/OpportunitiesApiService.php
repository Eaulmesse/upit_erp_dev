<?php

namespace App\Service;

use App\Repository\EmployeesRepository;
use Psr\Log\LoggerInterface;
use App\Repository\UsersRepository;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use App\Repository\QuotationsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Opportunities; // Remplacement de Contracts par Opportunities
use App\Repository\OpportunitiesRepository; // Remplacement de ContractsRepository par OpportunitiesRepository

class OpportunitiesApiService // Remplacement de ContractsApiService par OpportunitiesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, OpportunitiesRepository $opportunitiesRepository, CompaniesRepository $companyRepository, EmployeesRepository $employeesRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/opportunities', // Remplacement de contracts par opportunities
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $opportunitiesRepository, $companyRepository, $employeesRepository );

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, OpportunitiesRepository $opportunitiesRepository, CompaniesRepository $companyRepository, EmployeesRepository $employeesRepository): Response
    {
        $data = $session->get('api_data');

        foreach ($data as $opportunitiesData) {
            $this->opportunitiesToDatabase($opportunitiesData, $em, $companyRepository, $employeesRepository);
//            dd($this->opportunitiesToDatabase($opportunitiesData, $em, $companyRepository, $employeesRepository));
            $em->persist($this->opportunitiesToDatabase($opportunitiesData, $em, $companyRepository, $employeesRepository));
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $opportunitiesIdsInData = array_map(function ($opportunitiesData) {
            return $opportunitiesData['id'];
        }, $data);

        $allOpportunities = $opportunitiesRepository->findAll();
        foreach ($allOpportunities as $opportunities) {
            if (!in_array($opportunities->getId(), $opportunitiesIdsInData)) {
                $em->remove($opportunities);
            }
        }

        $this->saveOpportunities($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function opportunitiesToDatabase($opportunitiesData, EntityManagerInterface $em, CompaniesRepository $companyRepository, EmployeesRepository $employeesRepository,  ?Opportunities $opportunities = null): Opportunities
    {
        $opportunitiesId = $opportunitiesData['id'];
        $opportunities = $em->getRepository(Opportunities::class)->find($opportunitiesId);

        if ($opportunities === null) {
            $opportunities = new Opportunities();
            $opportunities->setId($opportunitiesId);
        }

        $opportunities->setId($opportunitiesData['id']);
        $opportunities->setName($opportunitiesData['name']);
        $opportunities->setComments($opportunitiesData['comments']);
        $opportunities->setAmount($opportunitiesData['amount']);
        $opportunities->setProbability($opportunitiesData['probability']);

        $dueDate = new \DateTime($opportunitiesData['due_date']);
        $opportunities->setDueDate($dueDate);

        $endDate = new \DateTime($opportunitiesData['end_date']);
        $opportunities->setEndDate($endDate);

        $opportunities->setIsWin($opportunitiesData['is_win']);
        $opportunities->setIsArchived($opportunitiesData['is_archived']);
        $opportunities->setUserName($opportunitiesData['user_name']);
        $opportunities->setPipName($opportunitiesData['pipe_name']);
        $opportunities->setPipStepName($opportunitiesData['pipe_step_name']);
        $opportunities->setCompany($companyRepository->find($opportunitiesData['company']['id']));


        foreach ($opportunitiesData['employees'] as $employees)  {
            if($employees['id'] != null) {
                $opportunities->setEmployees($employeesRepository->find($employees['id']));
            } else {
                $opportunities->setEmployees(null);
            }
        }

        return $opportunities;
    }

    private function saveOpportunities(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
