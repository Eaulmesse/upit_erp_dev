<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use App\Service\AddressesWebhookService;
use App\Service\CompaniesWebhookService;
use Doctrine\ORM\EntityManagerInterface;
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
}
