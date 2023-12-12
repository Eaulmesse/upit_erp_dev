<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\Projects;
use Psr\Log\LoggerInterface;
use App\Repository\UsersRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectsRepository;
use App\Repository\StatusesRepository;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use App\Repository\WorkforcesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProjectNaturesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjectWebhookService 
{

    private $logger;
    private $client;


    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
    }

    public function getWebhookProjects(Request $request, SessionInterface $session,  LoggerInterface $logger, EntityManagerInterface $em,ProjectsRepository $projectsRepository, CompaniesRepository $companiesRepository, ProjectNaturesRepository $projectNaturesRepository, UsersRepository $usersRepository, StatusesRepository $statusesRepository): Response
    {
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }

        $session->set('webhook_data', $response);
        $this->logger->info('Webhook received!', $response);

        $this->webhookProjectsFilter($session, $em, $projectsRepository, $companiesRepository, $projectNaturesRepository, $usersRepository, $statusesRepository);
        
        return new Response('Done!', Response::HTTP_OK);
    }


    private function createProject($webhookData, EntityManagerInterface $em, CompaniesRepository $companiesRepository, ProjectNaturesRepository $projectNaturesRepository, UsersRepository $usersRepository, StatusesRepository $statusesRepository): void {

        $this->logger->info('Creation project', $webhookData);
        $project = new Projects();
        $project->setId($webhookData['data']['id']);
        $this->mapDataToCompanies($project, $webhookData, $companiesRepository, $projectNaturesRepository, $usersRepository, $statusesRepository);
        $em->persist($project);
        $em->flush();
    }

    private function updateProject($webhookData, EntityManagerInterface $em, ProjectsRepository $projectsRepository, CompaniesRepository $companiesRepository, ProjectNaturesRepository $projectNaturesRepository, UsersRepository $usersRepository, StatusesRepository $statusesRepository): void {

        $this->logger->info('Update project', $webhookData);
        $project = $projectsRepository->find($webhookData["data"]["id"]);
        if (!$project) {
            throw new \Exception("Quotation with ID " . $webhookData["data"]["id"] . " not found.");
        }
        $this->mapDataToCompanies($project, $webhookData, $companiesRepository, $projectNaturesRepository, $usersRepository, $statusesRepository);
        $em->flush();
    }

    private function deleteProject($webhookData, EntityManagerInterface $em, ProjectsRepository $projectsRepository): void {

        $this->logger->INFO('Suppression: ', $webhookData);
        
        $project = $projectsRepository->find($webhookData["data"]["id"]);
        $em->remove($project);
        $em->flush();
    }

    private function mapDataToCompanies(Projects $projects, $webhookData, CompaniesRepository $companiesRepository, ProjectNaturesRepository $projectNaturesRepository, UsersRepository $usersRepository, StatusesRepository $statusesRepository): void {

        

        $projects->setName($webhookData['data']['name']);
        $projects->setnumber($webhookData['data']['number']);
        $projects->setComments($webhookData['data']['comments']);
        $projects->setEstimatedHours($webhookData['data']['estimated_hours']);
        $projects->setEstimatedCost($webhookData['data']['estimated_cost']);
        $projects->setEstimatedRevenue($webhookData['data']['estimated_revenue']);
        $projects->setActualHours($webhookData['data']['actual_hours']);
        $projects->setActualExpensesCost($webhookData['data']['actual_expenses_cost']);
        $projects->setActualTimetrackingsCost($webhookData['data']['actual_timetrackings_cost']);
        $projects->setActualConsumeProductsCost($webhookData['data']['actual_consume_products_cost']);
        $projects->setActualRevenue($webhookData['data']['actual_revenue']);
        $projects->setEstimatedStart(new \DateTime($webhookData['data']['estimated_start']));
        $projects->setActualStart(new \DateTime($webhookData['data']['actual_start']));
        $projects->setEstimatedEnd(new \DateTime($webhookData['data']['estimated_end']));
        $projects->setActualEnd(new \DateTime($webhookData['data']['actual_end']));
        
        if ($webhookData['data']['company_id'] != null) {
            $projects->setCompany($companiesRepository->find($webhookData['data']['company_id']));
        }

        if ($webhookData['data']['project_nature'] != null) {
            $projects->setProjectNatures($projectNaturesRepository->find($webhookData['data']['project_nature']['id']));
        }
        

        

        foreach ($webhookData['data']['workforces'] as $workforce) {
            if ($workforce != null) {
                $this->logger->info('Workforce!', $workforce);
                $projects->setUsers($usersRepository->find($workforce['idUser']));
            }
        }

        if ($webhookData['data']['statuses'] != null) {
            $projects->setStatuses($statusesRepository->find($webhookData['data']['statuses']['id']));
        }
    }

    public function webhookProjectsFilter(SessionInterface $session, EntityManagerInterface $em, ProjectsRepository $projectsRepository, CompaniesRepository $companiesRepository, ProjectNaturesRepository $projectNaturesRepository, UsersRepository $usersRepository, StatusesRepository $statusesRepository): Response {
        $webhookData = $session->get('webhook_data');
    
        switch ($webhookData['topic']) {
            case 'project.created':
                $this->createProject($webhookData, $em, $companiesRepository,$projectNaturesRepository, $usersRepository, $statusesRepository, $projectsRepository );
                break;
            case 'project.updated':
                $this->updateProject($webhookData, $em, $projectsRepository, $companiesRepository, $projectNaturesRepository, $usersRepository, $statusesRepository);
                break;
            case 'project.deleted':
                $this->deleteProject($webhookData, $em, $projectsRepository);
                break;
        }
    
        return new Response('Done!', Response::HTTP_OK);
    }
}