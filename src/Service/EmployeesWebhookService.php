<?php

namespace App\Service;

use App\Entity\Companies;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EmployeesRepository;
use App\Entity\Employees;



class EmployeesWebhookService
{
    private $logger;

    public function __construct(LoggerInterface $webhookLogger)
    {
        $this->logger = $webhookLogger;
    }

    public function getWebhookEmployees(Request $request, SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, EmployeesRepository $employeesRepository): Response
    {
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }

        $session->set('webhook_data', $response);
        
        $this->logger->info('Webhook Employees received!', $response);

        $this->webhookEmployeesFilter($session, $em, $logger, $employeesRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function creatingEmployees(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $webhookData = $session->get('webhook_data');

        $this->logger->INFO('Création: ', $webhookData);

        $companyId = $webhookData["data"]["company_id"];
        $company = $em->getRepository(Companies::class)->find($companyId);
            
        $dataEmployees = new Employees();

        $dataEmployees->setId($webhookData["data"]["id"]);
        $dataEmployees->setGender($webhookData["data"]["gender"]);
        $dataEmployees->setFirstname($webhookData["data"]["firstname"]);
        $dataEmployees->setLastname($webhookData["data"]["lastname"]);
        $dataEmployees->setEmail($webhookData["data"]["email"]);
        $dataEmployees->setPhoneNumber($webhookData["data"]["phone_number"]);
        $dataEmployees->setCellphoneNumber($webhookData["data"]["cellphone_number"]);
        $dataEmployees->setJob($webhookData["data"]["job"]);
        $dataEmployees->setIsBillingContact($webhookData["data"]["is_billing_contact"]);
        $dataEmployees->setCompany($company);

        $em->persist($dataEmployees);
           
        try{
            $em->flush();
        }
        catch(\Exception $e){
            error_log($e->getMessage());
        }

        return new Response(' Done!', Response::HTTP_OK);
    }

    public function updatingEmployees(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $webhookData = $session->get('webhook_data');
        $this->logger->INFO('Modification: ', $webhookData);

        $updatedEmployee = $em->getRepository(Employees::class)->find($webhookData["data"]["id"]);
        
        $em->remove($updatedEmployee);
        $em->flush();

        $companyId = $webhookData["data"]["company_id"];
        $company = $em->getRepository(Companies::class)->find($companyId);

        $dataEmployees = new Employees();

        $dataEmployees->setId($webhookData["data"]["id"]);
        $dataEmployees->setGender($webhookData["data"]["gender"]);
        $dataEmployees->setFirstname($webhookData["data"]["firstname"]);
        $dataEmployees->setLastname($webhookData["data"]["lastname"]);
        $dataEmployees->setEmail($webhookData["data"]["email"]);
        $dataEmployees->setPhoneNumber($webhookData["data"]["phone_number"]);
        $dataEmployees->setCellphoneNumber($webhookData["data"]["cellphone_number"]);
        $dataEmployees->setJob($webhookData["data"]["job"]);
        $dataEmployees->setIsBillingContact($webhookData["data"]["is_billing_contact"]);
        $dataEmployees->setCompany($company);
        
        $em->persist($dataEmployees);
       
        try{
            $em->flush();
        }
        catch(\Exception $e){
            error_log($e->getMessage());
        }

        return new Response(' Done!', Response::HTTP_OK);
    }

    public function deletingEmployees(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $webhookData = $session->get('webhook_data');
        $this->logger->INFO('Suppression: ', $webhookData);

        $updatedEmployee = $em->getRepository(Employees::class)->find($webhookData["data"]["id"]);
        
        $em->remove($updatedEmployee);
        $em->flush();
        return new Response(' Done!', Response::HTTP_OK);
    }

    #[Route('/webhook/companies/filter', name: 'app_webhook_companies_filter')]
    public function webhookEmployeesFilter(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger): Response 
    {
        $webhookData = $session->get('webhook_data');
        
        if (isset($webhookData['topic']) && $webhookData['topic'] === 'employee.created') {

            $this->creatingEmployees($session, $em);
            
        }  
        else if(isset($webhookData['topic']) && $webhookData['topic'] === 'employee.updated') {

            $this->updatingEmployees($session, $em);
            
        }
        else if(isset($webhookData['topic']) && $webhookData['topic'] === 'employee.deleted') {
        
            $this->deletingEmployees($session, $em);
        }

        return new Response(' Done!', Response::HTTP_OK);
    }
}






