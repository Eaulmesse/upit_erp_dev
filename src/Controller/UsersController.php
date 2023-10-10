<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\UsersRepository;

class UsersController extends AbstractController
{
    private $logger;
    private $client;

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->logger = $webhookLogger;
        $this->client = $client;
    }

    #[Route('/webhook/users', name: 'app_webhook_users', methods: 'POST')]
    public function getWebhookUsers(Request $request, SessionInterface $session, EntityManagerInterface $em, UsersRepository $usersRepository, LoggerInterface $logger): Response
    {
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }
        
        // Traitez vos données ici (par exemple, stockez-les dans une base de données, etc.)


        $session->set('webhook_data', $response);
        

        // Mise en log webhook.log
        $this->logger->info('Webhook users received!', $response);



        // $this->webhookCompaniesFilter($session, $em, $companiesRepository,$addressesRepository, $logger);


        return new Response('Received!', Response::HTTP_OK);
    }
    
}
