<?php

namespace App\Service;

use App\Repository\PayslipsRepository;
use App\Repository\ProjectsRepository;
use App\Repository\SupplierContractRepository;
use App\Repository\SuppliersRepository;
use App\Repository\WorkforcesRepository;
use Psr\Log\LoggerInterface;

use App\Repository\CompaniesRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Expenses;

use App\Repository\ExpensesRepository;
use App\Service\ExpenseLinesApiService;
use App\Repository\ExpenseLinesRepository;


class ExpensesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em,  ExpensesRepository $expensesRepository, SuppliersRepository $suppliersRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectsRepository $projectsRepository, SupplierContractRepository $supplierContractRepository, ExpenseLinesApiService $expenseLinesApiService, ExpenseLinesRepository $expenseLinesRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/expenses', // Remplacement de contracts par expenses
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $expensesRepository, $suppliersRepository, $companiesRepository, $workforcesRepository, $payslipsRepository, $projectsRepository, $supplierContractRepository, $expenseLinesApiService, $expenseLinesRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em,  ExpensesRepository $expensesRepository, SuppliersRepository $suppliersRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectsRepository $projectsRepository, SupplierContractRepository $supplierContractRepository, ExpenseLinesApiService $expenseLinesApiService, ExpenseLinesRepository $expenseLinesRepository): Response
    {
        $data = $session->get('api_data');
        
        foreach ($data as $expensesData) {
            
            $expenseLines = $expensesData['expense_lines'];

            
            $expenseLinesApiService->getData($session, $em, $expensesData, $expenseLines, $expenseLinesRepository, $expensesRepository);
            $em->persist($this->expensesToDatabase($expensesData, $em, $suppliersRepository,  $companiesRepository, $workforcesRepository, $payslipsRepository, $projectsRepository, $supplierContractRepository));
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $expensesIdsInData = array_map(function ($expensesData) {
            return $expensesData['id'];
        }, $data);

        $allExpenses = $expensesRepository->findAll();
        foreach ($allExpenses as $expenses) {
            if (!in_array($expenses->getId(), $expensesIdsInData)) {
                $em->remove($expenses);
            }
        }

        $this->saveExpenses($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function expensesToDatabase($expensesData, EntityManagerInterface $em, SuppliersRepository $suppliersRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectsRepository $projectsRepository, SupplierContractRepository $supplierContractRepository, ?Expenses $expenses = null): Expenses
    {
        $expensesId = $expensesData['id'];
        $expenses = $em->getRepository(Expenses::class)->find($expensesId);

        if ($expenses === null) {
            $expenses = new Expenses();
            $expenses->setId($expensesId);
        }

        $expenses->setId($expensesData['id']);
        $expenses->setTitle($expensesData['title']);
        $expenses->setDate(new \DateTime($expensesData['date']));
        $expenses->setNumber($expensesData['number']);
        $expenses->setCreationDate(new \DateTime($expensesData['creation_date']));
        $expenses->setLastUpdateDate(new \DateTime($expensesData['last_update_date']));
        $expenses->setPaidDate(new \DateTime($expensesData['paid_date']));
        $expenses->setExpectedPaymentDate(new \DateTime($expensesData['expected_payment_date']));
        $expenses->setPreTaxAmount($expensesData['pre_tax_amount']);
        $expenses->setTaxAmount($expensesData['tax_amount']);
        $expenses->setTotalAmount($expensesData['total_amount']);
        $expenses->setLeftToPay($expensesData['left_to_pay']);
        $expenses->setCurrency($expensesData['currency']);
        $expenses->setAccountingCode($expensesData['accounting_code']);
        $expenses->setAccountingCodeName($expensesData['accounting_code_name']);
        $expenses->setPublicPath($expensesData['public_path']);

        if($expensesData['supplier_contract_id'] !== null)
        {
            $expenses->setSupplierContract($supplierContractRepository->find($expensesData['supplier_contract_id']));
        }
        $expenses->setSupplier($suppliersRepository->find($expensesData['supplier_id']));
        $expenses->setSupplierName($expensesData['supplier_name']);

        if ($expensesData['company_id']) {
            $expenses->setCompany($companiesRepository->find($expensesData['company_id']));
        }


        if ($expensesData['workforce_id'] != null) {
            $expenses->setWorkforce($workforcesRepository->find($expensesData['workforce_id']));
        }


        if ($expensesData['payslip_id'] != null) {
            $expenses->setPayslips($payslipsRepository->find($expensesData['payslip_id']));
        }
        if ($expensesData['project_id'] != null) {
            $expenses->setProject($projectsRepository->find($expensesData['project_id']));
        }





        return $expenses;
    }

    private function saveExpenses(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
