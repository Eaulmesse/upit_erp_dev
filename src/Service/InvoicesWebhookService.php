<?php

namespace App\Service;

use App\Entity\Companies;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Invoices;


class InvoicesWebhookService
{
    private $logger;

    public function __construct(LoggerInterface $webhookLogger)
    {
        $this->logger = $webhookLogger;
    }

    public function getWebhookInvoices(Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $response = json_decode($request->getContent(), true);

        if ($response === null) {
            $this->logger->error('Invalid JSON received.');
            return new Response('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }

        $session->set('webhook_data', $response);
        
        $this->logger->info('Webhook Invoices received!', $response);

        $this->creatingInvoices($session, $em);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function creatingInvoices(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $webhookData = $session->get('webhook_data');

        $this->logger->INFO('Création: ', $webhookData);

        
        $dataInvoices = $this->mapToDatabase($webhookData, $em);
        
        $em->persist($dataInvoices);
           
        try {
            $em->flush();
        } catch(\Exception $e) {
            error_log($e->getMessage());
        }

        return new Response('Done!', Response::HTTP_OK);
    }

    public function mapToDatabase($webhookData, EntityManagerInterface $em, ?Invoices $invoices = null): Invoices 
    {
        $invoicesId = $webhookData['id'];
        $invoices = $em->getRepository(Companies::class)->find($invoicesId);

        if ($invoices === null) {
            $invoices = new Companies();
            $invoices->setId($invoicesId);
        }
        


        return $invoices;
    }


}




