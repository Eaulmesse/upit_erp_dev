<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Opportunities;
use App\Entity\Addresses;
use App\Repository\OpportunitiesRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use App\Repository\EmployeesRepository;

class OpportunitiesWebhookService 
{

    private $logger;
    private $client;


    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
    }

    public function getWebhookOpportunities(Request $request, SessionInterface $session,  LoggerInterface $logger, EntityManagerInterface $em, OpportunitiesRepository $opportunitiesRepository, CompaniesRepository $companiesRepository, EmployeesRepository $employeesRepository): Response
{
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }

        $session->set('webhook_data', $response);
        $this->logger->info('Webhook received!', $response);

        $this->webhookOpportunitiesFilter($session, $em, $opportunitiesRepository, $companiesRepository, $employeesRepository);
        
        return new Response('Done!', Response::HTTP_OK);
    }


    private function createCompany($webhookData, EntityManagerInterface $em, OpportunitiesRepository $opportunitiesRepository, CompaniesRepository $companiesRepository, EmployeesRepository $employeesRepository): void {

        $this->logger->info('Creation opportunity', $webhookData);
        $opportunity= new Opportunities();
        $this->mapDataToOpportunities($opportunity, $webhookData, $companiesRepository, $employeesRepository ,$opportunitiesRepository);
        $em->persist($opportunity);
        $em->flush();
    }

    private function updateCompany($webhookData, EntityManagerInterface $em, OpportunitiesRepository $opportunitiesRepository, CompaniesRepository $companiesRepository, EmployeesRepository $employeesRepository): void {

        $this->logger->info('Update opportunity', $webhookData);
        $opportunity= $opportunitiesRepository->find($webhookData["data"]["id"]);
        if (!$opportunity) {
            throw new \Exception("Quotation with ID " . $webhookData["data"]["id"] . " not found.");
        }
        $this->mapDataToOpportunities($opportunity, $webhookData, $companiesRepository, $employeesRepository ,$opportunitiesRepository);
        $em->flush();
    }

    private function deleteCompany($webhookData, EntityManagerInterface $em, OpportunitiesRepository $opportunitiesRepository, ): void {

        $this->logger->INFO('Suppression: ', $webhookData);
        
        $opportunity= $opportunitiesRepository->find($webhookData["data"]["id"]);
        $em->remove($opportunity);
        $em->flush();
    }

    private function mapDataToOpportunities(Opportunities $opportunity, $webhookData, CompaniesRepository $companiesRepository, EmployeesRepository $employeesRepository): void {

        $opportunity->setId($webhookData['data']['id']);
        $opportunity->setName($webhookData['data']['name']);
        $opportunity->setComments($webhookData['data']['comments']);
        $opportunity->setAmount($webhookData['data']['amount']);
        $opportunity->setProbability($webhookData['data']['probability']);

        $dueDate = new \DateTime($webhookData['data']['due_date']);
        $opportunity->setDueDate($dueDate);

        $endDate = new \DateTime($webhookData['data']['end_date']);
        $opportunity->setEndDate($endDate);

        $opportunity->setIsWin($webhookData['data']['is_win']);
        $opportunity->setIsArchived($webhookData['data']['is_archived']);
        $opportunity->setUserName($webhookData['data']['user_name']);
        $opportunity->setPipName($webhookData['data']['pipe_name']);
        $opportunity->setPipStepName($webhookData['data']['pipe_step_name']);
        $opportunity->setCompany($companiesRepository->find($webhookData['data']['company']['id']));


        foreach ($webhookData['data']['employees'] as $employees)  {
            if($employees['id'] != null) {
                $opportunity->setEmployees($employeesRepository->find($employees['id']));
            } else {
                $opportunity->setEmployees(null);
            }
        }

    }

    public function webhookOpportunitiesFilter(SessionInterface $session, EntityManagerInterface $em, OpportunitiesRepository $opportunitiesRepository, CompaniesRepository $companiesRepository, EmployeesRepository $employeesRepository): Response {
        $webhookData = $session->get('webhook_data');
    
        switch ($webhookData['topic']) {
            case 'opportunity.created':
                $this->createCompany($webhookData, $em, $opportunitiesRepository,$companiesRepository,$employeesRepository);
                break;
            case 'opportunity.updated':
                $this->updateCompany($webhookData, $em, $opportunitiesRepository, $companiesRepository,$employeesRepository);
                break;
            case 'opportunity.deleted':
                $this->deleteCompany($webhookData, $em, $opportunitiesRepository);
                break;
        }
    
        return new Response('Done!', Response::HTTP_OK);
    }
}





















