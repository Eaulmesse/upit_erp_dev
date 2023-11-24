<?php

namespace App\Controller;

use App\Entity\Addresses;
use App\Repository\ProjectNaturesRepository;
use App\Repository\SupplierContractRepository;
use App\Repository\TaxRatesRepository;
use App\Service\SupplierContractsApiService;
use App\Service\TaxRatesApiService;
use Psr\Log\LoggerInterface;
use App\Service\UsersApiService;

use App\Repository\UsersRepository;
use App\Service\PayslipsApiService;

use App\Service\ProductsApiService;
use App\Service\AddressesApiService;

use App\Service\CompaniesApiService;
use App\Service\ContractsApiService;

use App\Service\EmployeesApiService;
use App\Service\SuppliersApiService;

use App\Service\WorkforcesApiService;
use App\Repository\PayslipsRepository;

use App\Repository\ProductsRepository;
use App\Repository\AddressesRepository;

use App\Repository\CompaniesRepository;
use App\Repository\ContractsRepository;

use App\Repository\EmployeesRepository;
use App\Repository\SuppliersRepository;

use App\Repository\OpportunitiesRepository;
use App\Service\OpportunitiesApiService;

use App\Repository\ProjectsRepository;
use App\Repository\StatusesRepository;
use App\Service\ProjectsApiService;



use App\Repository\QuotationsRepository;
use App\Repository\WorkforcesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CallApiController extends AbstractController
{
    

    #[Route('/call/api/companies', name: 'app_call_companies_api')]
    public function callCompaniesService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, CompaniesRepository $companiesRepository, CompaniesApiService $companiesApiService)
    {
        return($companiesApiService->callAPI($session, $em, $logger, $companiesRepository));
    }

    #[Route('/call/api/addresses', name: 'app_call_addresses_api')]
    public function callAddressesService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, AddressesRepository $addressesRepository, AddressesApiService $addressesApiService, CompaniesRepository $companiesRepository)
    {
        return($addressesApiService->callAPI($session, $em, $logger, $addressesRepository, $companiesRepository));
    }

    #[Route('/call/api/users', name: 'app_call_users_api')]
    public function callUsersService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, UsersApiService $usersApiService, UsersRepository $usersRepository): Response
    {
        return $usersApiService->callAPI($session, $em, $logger, $usersRepository);
    }

    #[Route('/call/api/workforces', name: 'app_call_workforces_api')]
    public function callWorkforcesService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, WorkforcesApiService $workforcesApiService, WorkforcesRepository $workforcesRepository): Response
    {
        return $workforcesApiService->callAPI($session, $em, $logger, $workforcesRepository);
    }

    #[Route('/call/api/payslips', name: 'app_call_payslips_api')]
    public function callPayslipsService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, PayslipsApiService $payslipsApiService, PayslipsRepository $payslipsRepository, WorkforcesRepository $workforcesRepository): Response
    {
        return $payslipsApiService->callAPI($session, $em, $logger, $payslipsRepository, $workforcesRepository);
    }

    #[Route('/call/api/employees', name: 'app_call_employees_api')]
    public function callEmployeesService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, EmployeesApiService $employeesApiService, EmployeesRepository $employeesRepository, CompaniesRepository $companiesRepository): Response
    {
        return $employeesApiService->callAPI($session, $em, $logger, $employeesRepository, $companiesRepository);
    }

    #[Route('/call/api/products', name: 'app_call_products_api')]
    public function callProductsService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ProductsApiService $productsApiService, ProductsRepository $productsRepository): Response
    {
        return $productsApiService->callAPI($session, $em, $logger, $productsRepository);
    }

    #[Route('/call/api/suppliers', name: 'app_call_suppliers_api')]
    public function callSuppliersService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, SuppliersApiService $suppliersApiService, SuppliersRepository $suppliersRepository, CompaniesRepository $companiesRepository): Response
    {
        return $suppliersApiService->callAPI($session, $em, $logger, $suppliersRepository, $companiesRepository);
    }

    #[Route('/call/api/contracts', name: 'app_call_contracts_api')]
    public function callContractsService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ContractsApiService $contractsApiService, ContractsRepository $contractsRepository, CompaniesRepository $companiesRepository,  AddressesRepository $addressesRepository, UsersRepository $usersRepository, QuotationsRepository $quotationsRepository): Response
    {
        return $contractsApiService->callAPI($session, $em, $logger, $contractsRepository, $companiesRepository, $addressesRepository, $usersRepository, $quotationsRepository);
    }

    #[Route('/call/api/opportunities', name: 'app_call_opportunities_api')]
    public function callOpportunitiesService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, OpportunitiesRepository $opportunitiesRepository, CompaniesRepository $companyRepository, EmployeesRepository $employeesRepository, OpportunitiesApiService $opportunitiesApiService): Response
    {
        return $opportunitiesApiService->callAPI($session, $em, $logger, $opportunitiesRepository, $companyRepository, $employeesRepository);
    }

    #[Route('/call/api/projects', name: 'app_call_projects_api')]
    public function callProjectsService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, ProjectsRepository $projectsRepository, CompaniesRepository $companyRepository, StatusesRepository $statusesRepository, ProjectNaturesRepository $projectNaturesRepository, ProjectsApiService $projectsApiService, UsersRepository $usersRepository): Response
    {
        return $projectsApiService->callAPI($session, $em, $logger, $projectsRepository, $companyRepository, $statusesRepository, $projectNaturesRepository, $usersRepository);
    }

    #[Route('/call/api/taxRates', name: 'app_call_taxRates_api')]
    public function callTaxRatesService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, TaxRatesRepository $taxRatesRepository, TaxRatesApiService $taxRatesApiService): Response
    {
        return $taxRatesApiService->callAPI($session, $em, $logger, $taxRatesRepository);
    }

    #[Route('/call/api/supplierContracts', name: 'app_call_supplierContracts_api')]
    public function callSupplierContractsService(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger,  SupplierContractRepository $supplierContractRepository, SuppliersRepository $suppliersRepository, SupplierContractsApiService $supplierContractsApiService): Response
    {
        return $supplierContractsApiService->callAPI($session, $em, $logger, $supplierContractRepository, $suppliersRepository);
    }
}
