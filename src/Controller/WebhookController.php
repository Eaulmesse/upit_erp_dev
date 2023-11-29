<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Repository\UsersRepository;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use App\Repository\ContractsRepository;
use App\Repository\EmployeesRepository;
use App\Repository\QuotationsRepository;
use App\Service\AddressesWebhookService;
use App\Service\CompaniesWebhookService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ContractsWebhookService;
use App\Service\EmployeesWebhookService;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/webhook/employees', name: 'app_webhook_employees', methods: 'POST')]
    public function callWebhookEmployees(Request $request, SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, EmployeesRepository $employeesRepository, EmployeesWebhookService $employeesWebhookService): Response
    {
        $data = $employeesWebhookService->getWebhookEmployees($request, $session, $em, $logger, $employeesRepository);

        return $data;
    }
}
