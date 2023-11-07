<?php

namespace App\Controller;

use App\Entity\Payslips;
use Psr\Log\LoggerInterface;
use App\Repository\PayslipsRepository;
use App\Repository\WorkforcesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PayslipsController extends AbstractController
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $webhookLogger;
    }

    #[Route('/payslips/get', name: 'app_get_users')]
    public function callAPI(Request $request, SessionInterface $session, EntityManagerInterface $em, PayslipsRepository $payslipsRepository, LoggerInterface $logger, WorkforcesRepository $workforcesRepository): Response
    {
 
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/payslips',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                    // Ajoutez d'autres en-têtes si nécessaire
                ]
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);
        //dd($data);
        $this->dataCheck($session, $em, $payslipsRepository, $logger, $workforcesRepository);
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, PayslipsRepository $payslipsRepository, LoggerInterface $logger,  WorkforcesRepository $workforcesRepository): Response
    {   
        $data = $session->get('api_data');

        foreach($data as $payslipsData) {

            $inDatabaseId = $payslipsRepository->find($payslipsData['id']);

            if ($inDatabaseId == null) {
                
                $inDatabaseId = $this->payslipsToDatabase($payslipsData, $em, $workforcesRepository);
                $em->persist($inDatabaseId);
            }   
            else {
                $inDatabaseId = $this->payslipsToDatabase($payslipsData, $em, $workforcesRepository);
            } 
        }

        $payslipsIdsInData = array_map(function($payslipsData) {
            return $payslipsData['id'];
        }, $data);

        $allPayslips = $payslipsRepository->findall();
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
        
        // dd($payslipsData['workforce_id']);
        $payslips = New Payslips;



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
