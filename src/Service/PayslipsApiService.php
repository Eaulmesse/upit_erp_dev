<?php 
namespace App\Service;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Payslips;
use App\Repository\PayslipsRepository;
use App\Repository\WorkforcesRepository;

class PayslipsApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    
    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, PayslipsRepository $payslipsRepository, WorkforcesRepository $workforcesRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/payslips',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        // dd($data);
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $workforcesRepository,  $payslipsRepository);
        
    
        
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository): Response
    {   
        $data = $session->get('api_data');

        foreach ($data as $payslipsData) {
            $payslips = $this->payslipsToDatabase($payslipsData, $em, $workforcesRepository);
            $em->persist($payslips);
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $payslipsIdsInData = array_map(function ($payslipsData) {
            return $payslipsData['id'];
        }, $data);

        $allPayslips = $payslipsRepository->findAll();
        foreach ($allPayslips as $payslips) {
            if (!in_array($payslips->getId(), $payslipsIdsInData)) {
                $em->remove($payslips);
            }
        }

        $this->savePayslips($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function payslipsToDatabase($payslipsData, EntityManagerInterface $em, WorkforcesRepository $workforcesRepository, ?Payslips $payslips = null): Payslips
    {

        $workforcesId = $payslipsData['id'];
        $payslips = $em->getRepository(payslips::class)->find($workforcesId);

        if ($payslips === null) {
            $payslips = new Payslips();
            $payslips->setId($workforcesId);
        }

        
        $payslips->setId($payslipsData['id']);
        
        $payslips->setWorkforce($workforcesRepository->find($payslipsData['workforce_id']));
        
        $date = new \DateTime($payslipsData['date']);
        $payslips->setDate($date);

        $startDate = new \DateTime($payslipsData['start_date']);
        $payslips->setDate($startDate);

        $endDate = new \DateTime($payslipsData['end_date']);
        $payslips->setDate($endDate);

        $payslips->setNetSalary($payslipsData['net_salary']);
        $payslips->setTotalCost($payslipsData['total_cost']);
        $payslips->setTotalHours($payslipsData['total_hours']);




        return $payslips;
    }

    private function savePayslips(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}