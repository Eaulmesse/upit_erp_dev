<?php

namespace App\Service;

use App\Entity\Companies;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Expenses;
use App\Repository\CompaniesRepository;
use App\Repository\PayslipsRepository;
use App\Repository\ProjectRepository;
use App\Repository\SupplierContractRepository;
use App\Repository\SuppliersRepository;
use App\Repository\WorkforcesRepository;

class ExpensesWebhookService
{
    private $logger;

    public function __construct(LoggerInterface $webhookLogger)
    {
        $this->logger = $webhookLogger;
    }

    public function getWebhookExpenses(Request $request, SessionInterface $session, EntityManagerInterface $em, SuppliersRepository $supplierRepository, SupplierContractRepository $supplierContractRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectRepository $projectRepository): Response
    {
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }

        $session->set('webhook_data', $response);
        
        $this->logger->info('Webhook Expenses received!', $response);

        $this->webhookExpensesFilter($session, $em, $supplierRepository, $supplierContractRepository, $companiesRepository, $workforcesRepository, $payslipsRepository, $projectRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function creatingExpenses(SessionInterface $session, EntityManagerInterface $em, SuppliersRepository $supplierRepository, SupplierContractRepository $supplierContractRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectRepository $projectRepository): Response
    {
        $webhookData = $session->get('webhook_data');

        $this->logger->INFO('Création: ', $webhookData);

        $companyId = $webhookData["data"]["company_id"];
        $company = $em->getRepository(Companies::class)->find($companyId);
            
        $dataExpenses = $this->mapToDatabase($webhookData, $em, $supplierRepository, $supplierContractRepository, $companiesRepository, $workforcesRepository, $payslipsRepository, $projectRepository);
        
        $em->persist($dataExpenses);
           
        try {
            $em->flush();
        } catch(\Exception $e) {
            error_log($e->getMessage());
        }

        return new Response('Done!', Response::HTTP_OK);
    }

    public function updatingExpenses(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $webhookData = $session->get('webhook_data');
        $this->logger->INFO('Modification: ', $webhookData);

        $updatedExpense = $em->getRepository(Expenses::class)->find($webhookData["data"]["id"]);
        
        // Mettez à jour l'entité Expenses comme nécessaire
        // ...

        try {
            $em->flush();
        } catch(\Exception $e) {
            error_log($e->getMessage());
        }

        return new Response('Done!', Response::HTTP_OK);
    }

    public function deletingExpenses(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $webhookData = $session->get('webhook_data');
        $this->logger->INFO('Suppression: ', $webhookData);

        $updatedExpense = $em->getRepository(Expenses::class)->find($webhookData["data"]["id"]);
        
        $em->remove($updatedExpense);
        $em->flush();

        return new Response('Done!', Response::HTTP_OK);
    }

    public function mapToDatabase($webhookData, EntityManagerInterface $em, SuppliersRepository $supplierRepository, SupplierContractRepository $supplierContractRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectRepository $projectRepository, ?Expenses $expenses = null): Expenses 
    {
        $expensesId = $webhookData['id'];
        $expenses = $em->getRepository(Companies::class)->find($expensesId);

        if ($expenses === null) {
            $expenses = new Companies();
            $expenses->setId($expensesId);
        }
        
        $expenses->setTitle($webhookData['title']);

        $expenses->setNumber($webhookData['number']);

        $date = new \DateTime($webhookData['date']);
        $expenses->setDate($date);

        $creationDate = new \DateTime($webhookData['creation_date']);
        $expenses->setCreationDate($creationDate);

        $lastUpdateDate = new \DateTime($webhookData['last_update_date']);
        $expenses->setLastUpdateDate($lastUpdateDate);

        $paidDate = new \DateTime($webhookData['paid_date']);
        $expenses->setPaidDate($paidDate);

        $expectedPaymentDate = new \DateTime($webhookData['expected_payment_date']);
        $expenses->setExpectedPaymentDate($expectedPaymentDate);

        $expenses->setPreTaxAmount($webhookData['pre_tax_amount']);
        $expenses->setTaxAmount($webhookData['tax_amount']);
        $expenses->setTotalAmount($webhookData['total_amount']);
        $expenses->setLeftToPay($webhookData['left_to_pay']);

        $expenses->setCurrency($webhookData['currency']);
        $expenses->setAccountingCode($webhookData['accounting_code']);
        $expenses->setAccountingCodeName($webhookData['accounting_code_name']);

        $expenses->setPublicPath($webhookData['public_path']);

        // TODO: Supplier_Contract_ID
        $expenses->setSupplier($supplierRepository->find($webhookData['supplier_id']));

        // dd($supplierContractRepository->find($webhookData['supplier_contract_id']));

        
        if($webhookData['supplier_contract_id'] !== null)
        {
            $expenses->setSupplierContract($supplierContractRepository->find($webhookData['supplier_contract_id']));
        }
        
        
        
        $expenses->setSupplierName($webhookData['supplier_name']);

        if($webhookData['company_id'] !== null)
        {
            $expenses->setCompany($companiesRepository->find($webhookData['company_id']));
        }
        
        if($webhookData['workforce_id'] !== null)
        {
            $expenses->setWorkforce($workforcesRepository->find($webhookData['workforce_id']));
        }
        
        if($webhookData['payslip_id'] !== null)
        {
            $expenses->setPayslips($payslipsRepository->find($webhookData['payslip_id']));
        }

        if($webhookData['project_id'] !== null)
        {
            $expenses->setProject($projectRepository->find($webhookData['project_id']));
        }

        


        return $expenses;
    }

    public function webhookExpensesFilter(SessionInterface $session, EntityManagerInterface $em, SuppliersRepository $supplierRepository, SupplierContractRepository $supplierContractRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectRepository $projectRepository): Response 
    {
        $webhookData = $session->get('webhook_data');
        
        if (isset($webhookData['topic']) && $webhookData['topic'] === 'expense.created') {
            $this->creatingExpenses($session, $em, $supplierRepository, $supplierContractRepository, $companiesRepository, $workforcesRepository, $payslipsRepository, $projectRepository);
        }  
        else if (isset($webhookData['topic']) && $webhookData['topic'] === 'expense.updated') {
            $this->updatingExpenses($session, $em);
        }
        else if (isset($webhookData['topic']) && $webhookData['topic'] === 'expense.deleted') {
            $this->deletingExpenses($session, $em);
        }

        return new Response('Done!', Response::HTTP_OK);
    }
}




