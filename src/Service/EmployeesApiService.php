<?php 
namespace App\Service;

use App\Entity\Employees;
use Psr\Log\LoggerInterface;
use App\Repository\CompaniesRepository;
use App\Repository\EmployeesRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EmployeesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $verificationNumeroLogger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $verificationNumeroLogger;
    }

    
    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, EmployeesRepository $employeesRepository, CompaniesRepository $companiesRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/employees',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        // dd($data);
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $employeesRepository,  $companiesRepository);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, EmployeesRepository $employeesRepository, CompaniesRepository $companiesRepository): Response
    {   
        $data = $session->get('api_data');

        foreach ($data as $employeesData) {

            if(isset($employeesData["cellphone_number"])  || isset($employeesData["phone_number"])) {
                $employeesData = $this->mapContactPhoneNumber($employeesData);
            }
            
            $employees = $this->employeesToDatabase($employeesData, $em, $employeesRepository, $companiesRepository);
            $em->persist($employees);
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $employeesIdsInData = array_map(function ($employeesData) {
            return $employeesData['id'];
        }, $data);

        $allEmployees = $employeesRepository->findAll();
        foreach ($allEmployees as $employees) {
            if (!in_array($employees->getId(), $employeesIdsInData)) {
                $em->remove($employees);
            }
        }

        $this->saveEmployees($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    public function employeesToDatabase($employeesData, EntityManagerInterface $em, EmployeesRepository $employeesRepository, CompaniesRepository $companiesRepository, ?Employees $employees = null): Employees
    {

        $employyesId = $employeesData['id'];
        $employees = $em->getRepository(Employees::class)->find($employyesId);

        if ($employees === null) {
            $employees = new Employees();
            $employees->setId($employyesId);
        }

        $employees->setGender($employeesData["gender"]);
        $employees->setFirstname($employeesData["firstname"]);
        $employees->setLastname($employeesData["lastname"]);
        $employees->setEmail($employeesData["email"]);
        $employees->setPhoneNumber($employeesData["phone_number"]);
        $employees->setCellphoneNumber($employeesData["cellphone_number"]);
        $employees->setJob($employeesData["job"]);
        $employees->setIsBillingContact($employeesData["is_billing_contact"]);
        $company = $companiesRepository->find($employeesData['company_id']);
        $employees->setCompany($company);
        
        return $employees;
    }

    public function saveEmployees(EntityManagerInterface $em): void
    {
        $em->flush();
    }

    public function mapContactPhoneNumber($employeesData): array
    {
        $pattern = '/^\+33[1-9]/';
        
        $modified = false;

        // FORMATTAGE CELLPHONE
        if(str_contains($employeesData["cellphone_number"], " ")) {
            $employeesData["cellphone_number"] = str_replace(" ", "", $employeesData["cellphone_number"]);
            $modified = true;
        }
        
        if(str_starts_with($employeesData["cellphone_number"], "0")) {
            $employeesData["cellphone_number"] = "+33" . ltrim($employeesData["cellphone_number"], '0');
            $modified = true;
        } 
        else if (str_starts_with($employeesData["cellphone_number"], "+330")) {
            $employeesData["cellphone_number"] = preg_replace('/^\+330/', '+33', $employeesData["cellphone_number"]);
            $modified = true;
        } 
        

        // FORMATTAGE PHONE
        if(str_contains($employeesData["phone_number"], " ")) {
            $employeesData["phone_number"] = str_replace(" ", "", $employeesData["phone_number"]);
            $modified = true;
        }
        
        if(str_starts_with($employeesData["phone_number"], "0")) {
            $employeesData["phone_number"] = "+33" . ltrim($employeesData["phone_number"], '0');
            $modified = true;
        } 
        else if (str_starts_with($employeesData["phone_number"], "+330")) {
            $employeesData["phone_number"] = preg_replace('/^\+330/', '+33', $employeesData["phone_number"]);
            $modified = true;
        } 



        if (preg_match($pattern, $employeesData["phone_number"]) || $employeesData["phone_number"] == null && preg_match($pattern, $employeesData["cellphone_number"]) || $employeesData["cellphone_number"] == null) {

            if($modified) {
                $this->patchContact($employeesData);
            }

            return $employeesData;
        }
        else {
            $this->logger->INFO('Exception: ', $employeesData);
            return $employeesData;
        }
    }

    public function patchContact($employeesData): void
    {
        $data = [
            "gender" => $employeesData["gender"],
            "firstname" => $employeesData["firstname"],
            "lastname" => $employeesData["lastname"],
            "email" => $employeesData["email"],
            "phone_number" => $employeesData["phone_number"],
            "cellphone_number" => $employeesData["cellphone_number"],
        ];

        $jsonData = json_encode($data);
        $client = HttpClient::create();

        $patch = $client->request('PATCH', 'https://axonaut.com/api/v2/employees/' . $employeesData['id'], [
            'headers' => [
                'userApiKey' => $_ENV['API_KEY'],
                'Content-Type' => 'application/json',
            ],
            'body' => $jsonData
        ]);

        
    }


}