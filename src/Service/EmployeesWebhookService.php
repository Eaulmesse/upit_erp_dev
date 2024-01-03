<?php

namespace App\Service;

use App\Entity\Companies;
use App\Entity\Employees;
use Psr\Log\LoggerInterface;
use App\Repository\EmployeesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



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

        if(isset($webhookData['data']["cellphone_number"])  || isset($webhookData['data']["phone_number"])) {
            $webhookData = $this->mapContactPhoneNumber($webhookData);
        }

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

        if(isset($webhookData['data']["cellphone_number"])  || isset($webhookData['data']["phone_number"])) {
            $webhookData = $this->mapContactPhoneNumber($webhookData);
        }


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

    public function mapContactPhoneNumber($webhookData): array
    {
        $pattern = '/^\+33[1-9]/';
        
        $modified = false;

        // FORMATTAGE CELLPHONE
        if(str_contains($webhookData['data']["cellphone_number"], " ")) {
            $webhookData['data']["cellphone_number"] = str_replace(" ", "", $webhookData['data']["cellphone_number"]);
            $modified = true;
        }
        
        if(str_starts_with($webhookData['data']["cellphone_number"], "0")) {
            $webhookData['data']["cellphone_number"] = "+33" . ltrim($webhookData['data']["cellphone_number"], '0');
            $modified = true;
        } 
        else if (str_starts_with($webhookData['data']["cellphone_number"], "+330")) {
            $webhookData['data']["cellphone_number"] = preg_replace('/^\+330/', '+33', $webhookData['data']["cellphone_number"]);
            $modified = true;
        } 
        
        // FORMATTAGE PHONE
        if(str_contains($webhookData['data']["phone_number"], " ")) {
            $webhookData['data']["phone_number"] = str_replace(" ", "", $webhookData['data']["phone_number"]);
            $modified = true;
        }
        
        if(str_starts_with($webhookData['data']["phone_number"], "0")) {
            $webhookData['data']["phone_number"] = "+33" . ltrim($webhookData['data']["phone_number"], '0');
            $modified = true;
        } 
        else if (str_starts_with($webhookData['data']["phone_number"], "+330")) {
            $webhookData['data']["phone_number"] = preg_replace('/^\+330/', '+33', $webhookData['data']["phone_number"]);
            $modified = true;
        } 


        if (preg_match($pattern, $webhookData['data']["phone_number"]) || $webhookData['data']["phone_number"] == null && preg_match($pattern, $webhookData['data']["cellphone_number"]) || $webhookData['data']["cellphone_number"] == null) {

            if($modified) {
                $this->patchContact($webhookData);
            }

            return $webhookData;
        }
        else {
            $this->logger->INFO('Exception: ', $webhookData['data']);
            return $webhookData;
        }
    }

    public function patchContact($webhookData): void
    {
        $data = [
            "gender" => $webhookData['data']["gender"],
            "firstname" => $webhookData['data']["firstname"],
            "lastname" => $webhookData['data']["lastname"],
            "email" => $webhookData['data']["email"],
            "phone_number" => $webhookData['data']["phone_number"],
            "cellphone_number" => $webhookData['data']["cellphone_number"],
        ];

        $jsonData = json_encode($data);
        $client = HttpClient::create();

        $patch = $client->request('PATCH', 'https://axonaut.com/api/v2/employees/' . $webhookData['data']['id'], [
            'headers' => [
                'userApiKey' => $_ENV['API_KEY'],
                'Content-Type' => 'application/json',
            ],
            'body' => $jsonData
        ]);

        
    }
}






