<?php

namespace App\Controller;

use App\Entity\ExpenseLines;
use App\Entity\Expenses;
use App\Repository\CompaniesRepository;
use App\Repository\ExpenseLinesRepository;
use Psr\Log\LoggerInterface;
use App\Repository\ExpensesRepository;
use App\Repository\PayslipsRepository;
use App\Repository\ProjectsRepository;
use App\Repository\SupplierContractRepository;
use App\Repository\SuppliersRepository;
use App\Repository\WorkforcesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExpensesController extends AbstractController
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $webhookLogger;
    }

    #[Route('/expenses/get', name: 'app_get_expenses')]
    public function callAPI(Request $request, SessionInterface $session, EntityManagerInterface $em, ExpensesRepository $expensesRepository, LoggerInterface $logger, SuppliersRepository $supplierRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectsRepository $projectsRepository, SupplierContractRepository $supplierContractRepository): Response
    {
 
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/expenses',
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
        $this->dataCheck($session, $em, $expensesRepository, $logger, $supplierRepository, $companiesRepository, $workforcesRepository, $payslipsRepository, $projectsRepository, $supplierContractRepository);
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, ExpensesRepository $expensesRepository, LoggerInterface $logger, SuppliersRepository $supplierRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectsRepository $projectsRepository, SupplierContractRepository $supplierContractRepository): Response
    {   
        $data = $session->get('api_data');

        foreach($data as $expensesData) {

            $inDatabaseId = $expensesRepository->find($expensesData['id']);

            if ($inDatabaseId == null) {
                
                $inDatabaseId = $this->expensesToDatabase($expensesData, $em, $supplierRepository, $companiesRepository, $workforcesRepository, $payslipsRepository, $projectsRepository, $expensesRepository, $supplierContractRepository);
            }   
            else {
                $inDatabaseId = $this->expensesToDatabase($expensesData, $em, $supplierRepository, $companiesRepository, $workforcesRepository, $payslipsRepository, $projectsRepository, $expensesRepository, $supplierContractRepository);
            } 
        }

        $expensesIdsInData = array_map(function($expensesData) {
            return $expensesData['id'];
        }, $data);

        $allExpenses = $expensesRepository->findall();
        foreach ($allExpenses as $expenses) {
            if (!in_array($expenses->getId(), $expensesIdsInData)) {
                $em->remove($expenses);
            }
        }

        $this->saveExpenses($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function expensesToDatabase($expensesData, EntityManagerInterface $em, SuppliersRepository $supplierRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectsRepository $projectsRepository, ExpensesRepository $expensesRepository, SupplierContractRepository $supplierContractRepository,  ?Expenses $expenses = null): Expenses
    {        
       // CREATION EXPENSE
        $expenses = New Expenses;

        $expenses->setId($expensesData['id']);
        $expenses->setTitle($expensesData['title']);

        $expenses->setNumber($expensesData['number']);

        $date = new \DateTime($expensesData['date']);
        $expenses->setDate($date);

        $creationDate = new \DateTime($expensesData['creation_date']);
        $expenses->setCreationDate($creationDate);

        $lastUpdateDate = new \DateTime($expensesData['last_update_date']);
        $expenses->setLastUpdateDate($lastUpdateDate);

        $paidDate = new \DateTime($expensesData['paid_date']);
        $expenses->setPaidDate($paidDate);

        $expectedPaymentDate = new \DateTime($expensesData['expected_payment_date']);
        $expenses->setExpectedPaymentDate($expectedPaymentDate);

        $expenses->setPreTaxAmount($expensesData['pre_tax_amount']);
        $expenses->setTaxAmount($expensesData['tax_amount']);
        $expenses->setTotalAmount($expensesData['total_amount']);
        $expenses->setLeftToPay($expensesData['left_to_pay']);

        $expenses->setCurrency($expensesData['currency']);
        $expenses->setAccountingCode($expensesData['accounting_code']);
        $expenses->setAccountingCodeName($expensesData['accounting_code_name']);

        $expenses->setPublicPath($expensesData['public_path']);

        // TODO: Supplier_Contract_ID
        $expenses->setSupplier($supplierRepository->find($expensesData['supplier_id']));

        // dd($supplierContractRepository->find($expensesData['supplier_contract_id']));

        
        if($expensesData['supplier_contract_id'] !== null)
        {
            $expenses->setSupplierContract($supplierContractRepository->find($expensesData['supplier_contract_id']));
        }
        
        
        
        $expenses->setSupplierName($expensesData['supplier_name']);

        if($expensesData['company_id'] !== null)
        {
            $expenses->setCompany($companiesRepository->find($expensesData['company_id']));
        }
        
        if($expensesData['workforce_id'] !== null)
        {
            $expenses->setWorkforce($workforcesRepository->find($expensesData['workforce_id']));
        }
        
        if($expensesData['payslip_id'] !== null)
        {
            $expenses->setPayslips($payslipsRepository->find($expensesData['payslip_id']));
        }

        if($expensesData['project_id'] !== null)
        {
            $expenses->setProject($projectsRepository->find($expensesData['project_id']));
        }

        $em->persist($expenses);



        foreach($expensesData['expense_lines'] as $line)
        {
            $expenseLine = new ExpenseLines;

            $expenseLine->setTitle($line['title']);
            $expenseLine->setQuantity($line['quantity']);
            $expenseLine->setTotalPreTaxAmount($line['total_pre_tax_amount']);
            $expenseLine->setAccountingCode($line['accounting_code']);

            $expenseLine->setExpenses($expensesRepository->find($expensesData['id']));
            
            $em->persist($expenseLine);
        }



        return $expenses;
    }

    private function saveExpenses(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
