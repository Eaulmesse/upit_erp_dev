<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Repository\UsersRepository;
use App\Repository\ProjectRepository;
use App\Repository\PayslipsRepository;
use App\Repository\ProductsRepository;
use App\Repository\ProjectsRepository;
use App\Repository\StatusesRepository;
use App\Service\ProjectWebhookService;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use App\Repository\ContractsRepository;
use App\Repository\EmployeesRepository;
use App\Repository\SuppliersRepository;
use App\Service\ExpensesWebhookService;
use App\Service\ProductsWebhookService;
use App\Repository\QuotationsRepository;
use App\Repository\WorkforcesRepository;
use App\Service\AddressesWebhookService;
use App\Service\CompaniesWebhookService;
use App\Service\ContractsWebhookService;
use App\Service\EmployeesWebhookService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\QuotationLinesApiService;
use App\Service\QuotationsWebhookService;
use App\Repository\OpportunitiesRepository;
use App\Repository\ProjectNaturesRepository;
use App\Repository\QuotationLinesRepository;
use App\Service\OpportunitiesWebhookService;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SupplierContractRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebhookController extends AbstractController
{
    #[Route('/webhook/companies', name: 'app_webhook_companies')]
    public function callWebhookCompanies(Request $request, SessionInterface $session, LoggerInterface $logger, EntityManagerInterface $em, CompaniesRepository $companiesRepository, AddressesRepository $addressesRepository, CompaniesWebhookService $companiesWebhookService, AddressesWebhookService $addressesWebhookService): Response
    {
        $data = $companiesWebhookService->getWebhookCompanies($request, $session, $logger, $em, $companiesRepository, $addressesRepository, $addressesWebhookService);

        return $data;
    }

    #[Route('/webhook/contracts', name: 'app_webhook_employees')]
    public function callWebhookContracts(Request $request, SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ContractsRepository $contractsRepository, UsersRepository $usersRepository, CompaniesRepository $companiesRepository, AddressesRepository $addressesRepository, QuotationsRepository $quotationsRepository, ContractsWebhookService $contractsWebhookService): Response
    {
        $data = $contractsWebhookService->getWebhookContracts($request, $session, $em, $logger, $contractsRepository, $usersRepository, $companiesRepository, $addressesRepository, $quotationsRepository);

        return $data;
    }

    #[Route('/webhook/employees', name: 'app_webhook_employees')]
    public function callWebhookEmployees(Request $request, SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, EmployeesRepository $employeesRepository, EmployeesWebhookService $employeesWebhookService): Response
    {
        $data = $employeesWebhookService->getWebhookEmployees($request, $session, $em, $logger, $employeesRepository);

        return $data;
    }

    #[Route('/webhook/expenses', name: 'app_expenses_employees')]
    public function callWebhookExpenses(Request $request, SessionInterface $session, EntityManagerInterface $em, SuppliersRepository $supplierRepository, SupplierContractRepository $supplierContractRepository, CompaniesRepository $companiesRepository, WorkforcesRepository $workforcesRepository, PayslipsRepository $payslipsRepository, ProjectsRepository $projectRepository, ExpensesWebhookService $expensesWebhookService): Response
    {
        $data = $expensesWebhookService->getWebhookExpenses($request, $session, $em, $supplierRepository, $supplierContractRepository, $companiesRepository, $workforcesRepository, $payslipsRepository, $projectRepository);

        return $data;
    }

    #[Route('/webhook/opportunities', name: 'app_opportunities_employees')]
    public function callWebhookOpportunities(Request $request, SessionInterface $session, EntityManagerInterface $em, OpportunitiesWebhookService $opportunitiesWebhookService, LoggerInterface $logger, OpportunitiesRepository $opportunitiesRepository,CompaniesRepository $companiesRepository, EmployeesRepository $employeesRepository): Response
    {
        $data = $opportunitiesWebhookService->getWebhookOpportunities($request, $session, $logger, $em, $opportunitiesRepository, $companiesRepository, $employeesRepository);

        return $data;
    }

    #[Route('/webhook/products', name: 'app_products_employees')]
    public function callWebhookProducts(Request $request, SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ProductsWebhookService $productsWebhookService, ProductsRepository $productsRepository): Response
    {
        $data = $productsWebhookService->getWebhookProducts($request, $session, $logger, $em, $productsRepository);

        return $data;
    }

    #[Route('/webhook/projects', name: 'app_webhook_projects')]
    public function callWebhookProjects(Request $request, SessionInterface $session,  LoggerInterface $logger, EntityManagerInterface $em, ProjectsRepository $projectsRepository, CompaniesRepository $companiesRepository, ProjectNaturesRepository $projectNaturesRepository, UsersRepository $usersRepository, StatusesRepository $statusesRepository, ProjectWebhookService $projectWebhookService): Response
    {
        $data = $projectWebhookService->getWebhookProjects($request, $session, $logger, $em, $projectsRepository, $companiesRepository, $projectNaturesRepository, $usersRepository, $statusesRepository,$projectWebhookService);

        return $data;
    }

    #[Route('/webhook/quotations', name: 'app_webhook_quotations')]
    public function callWebhookQuotations(QuotationsWebhookService $quotationsWebhookService, Request $request, SessionInterface $session,  LoggerInterface $logger, EntityManagerInterface $em, UsersRepository $usersRepository, CompaniesRepository $companiesRepository, ProjectsRepository $projectsRepository, ContractsRepository $contractsRepository, OpportunitiesRepository $opportunitiesRepository, QuotationsRepository $quotationsRepository, QuotationLinesApiService $quotationLinesApiService, QuotationLinesRepository $quotationLinesRepository, ProductsRepository $productsRepository): Response
    {
        $data = $quotationsWebhookService->getWebhookQuotations($request, $session, $logger, $em, $usersRepository, $companiesRepository, $projectsRepository, $contractsRepository, $opportunitiesRepository, $quotationsRepository, $quotationLinesApiService, $quotationLinesRepository, $productsRepository);

        return $data;
    }
}
