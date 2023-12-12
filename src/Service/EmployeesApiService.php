<?php 
namespace App\Service;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Employees;
use App\Repository\EmployeesRepository;
use App\Repository\CompaniesRepository;

class EmployeesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
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

    private function employeesToDatabase($employeesData, EntityManagerInterface $em, EmployeesRepository $employeesRepository, CompaniesRepository $companiesRepository, ?Employees $employees = null): Employees
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

    private function saveEmployees(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}