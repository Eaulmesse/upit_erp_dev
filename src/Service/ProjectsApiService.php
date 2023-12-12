<?php

namespace App\Service;

use App\Repository\CompaniesRepository;
use App\Repository\ProjectNaturesRepository;
use App\Repository\StatusesRepository;
use App\Repository\WorkforcesRepository;
use Psr\Log\LoggerInterface;
use App\Repository\UsersRepository;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Projects; // Remplacement de Contracts par Projects
use App\Repository\ProjectsRepository; // Remplacement de ContractsRepository par ProjectsRepository

class ProjectsApiService // Remplacement de ContractsApiService par ProjectsApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ProjectsRepository $projectsRepository, CompaniesRepository $companyRepository, StatusesRepository $statusesRepository, ProjectNaturesRepository $projectNaturesRepository, UsersRepository $usersRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/projects', // Remplacement de contracts par projects
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $companyRepository, $projectsRepository, $statusesRepository, $projectNaturesRepository, $usersRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, CompaniesRepository $companyRepository, ProjectsRepository $projectsRepository, StatusesRepository $statusesRepository, ProjectNaturesRepository $projectNaturesRepository, UsersRepository $usersRepository): Response
    {
        $data = $session->get('api_data');

        foreach ($data as $projectsData) {

            $em->persist($this->projectsToDatabase($projectsData, $em, $companyRepository, $statusesRepository, $projectNaturesRepository, $usersRepository));
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $projectsIdsInData = array_map(function ($projectsData) {
            return $projectsData['id'];
        }, $data);

        $allProjects = $projectsRepository->findAll();
        foreach ($allProjects as $projects) {
            if (!in_array($projects->getId(), $projectsIdsInData)) {
                $em->remove($projects);
            }
        }

        $this->saveProjects($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function projectsToDatabase($projectsData, EntityManagerInterface $em, CompaniesRepository $companyRepository, StatusesRepository $statusesRepository, ProjectNaturesRepository $projectNaturesRepository, UsersRepository $usersRepository,  ?Projects $projects = null): Projects
    {
        $projectsId = $projectsData['id'];
        $projects = $em->getRepository(Projects::class)->find($projectsId);

        if ($projects === null) {
            $projects = new Projects();
            $projects->setId($projectsId);
        }

        $projects->setName($projectsData['name']);
        $projects->setnumber($projectsData['number']);
        if ($projectsData['company_id'] != null) {
            $projects->setCompany($companyRepository->find($projectsData['company_id']));
        } else {
            $projects->setCompany(null);
        }
        
        $projects->setComments($projectsData['comments']);
        $projects->setEstimatedHours($projectsData['estimated_hours']);
        $projects->setEstimatedCost($projectsData['estimated_cost']);
        $projects->setEstimatedRevenue($projectsData['estimated_revenue']);
        $projects->setActualHours($projectsData['actual_hours']);
        $projects->setActualExpensesCost($projectsData['actual_expenses_cost']);
        $projects->setActualTimetrackingsCost($projectsData['actual_timetrackings_cost']);
        $projects->setActualConsumeProductsCost($projectsData['actual_consume_products_cost']);
        $projects->setActualRevenue($projectsData['actual_revenue']);
        $projects->setEstimatedStart(new \DateTime($projectsData['estimated_start']));
        $projects->setActualStart(new \DateTime($projectsData['actual_start']));
        $projects->setEstimatedEnd(new \DateTime($projectsData['estimated_end']));
        $projects->setActualEnd(new \DateTime($projectsData['actual_end']));

        if ($projectsData['project_nature'] != null) {
            $projects->setProjectNatures($projectNaturesRepository->find($projectsData['project_nature']['id']));
        }

        foreach ($projectsData['workforces'] as $workforce) {
            if ($workforce != null) {
                $projects->setUsers($usersRepository->find($workforce['idUser']));
            }

        }

        if ($projectsData['statuses'] != null) {
            $projects->setStatuses($statusesRepository->find($projectsData['statuses']['id']));
        } else {
            $projects->setStatuses(null);
        }
        

        return $projects;
    }

    private function saveProjects(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
