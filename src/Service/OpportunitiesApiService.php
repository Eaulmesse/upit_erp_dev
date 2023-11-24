<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use App\Repository\UsersRepository;
use App\Repository\AddressesRepository;
use App\Repository\CompaniesRepository;
use App\Repository\QuotationsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Opportunities; // Remplacement de Contracts par Opportunities
use App\Repository\OpportunitiesRepository; // Remplacement de ContractsRepository par OpportunitiesRepository

class OpportunitiesApiService // Remplacement de ContractsApiService par OpportunitiesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, OpportunitiesRepository $opportunitiesRepository, CompaniesRepository $companyRepository, AddressesRepository $addressesRepository, UsersRepository $usersRepository, QuotationsRepository $quotationsRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/opportunities', // Remplacement de contracts par opportunities
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $usersRepository, $opportunitiesRepository, $addressesRepository, $companyRepository, $quotationsRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, UsersRepository $usersRepository, OpportunitiesRepository $opportunitiesRepository, AddressesRepository $addressesRepository, CompaniesRepository $companyRepository, QuotationsRepository $quotationsRepository): Response
    {
        $data = $session->get('api_data');

        foreach ($data as $opportunitiesData) {
            $this->opportunitiesToDatabase($opportunitiesData, $em, $usersRepository, $companyRepository, $addressesRepository, $quotationsRepository);
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $opportunitiesIdsInData = array_map(function ($opportunitiesData) {
            return $opportunitiesData['id'];
        }, $data);

        $allOpportunities = $opportunitiesRepository->findAll();
        foreach ($allOpportunities as $opportunities) {
            if (!in_array($opportunities->getId(), $opportunitiesIdsInData)) {
                $em->remove($opportunities);
            }
        }

        $this->saveOpportunities($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function opportunitiesToDatabase($opportunitiesData, EntityManagerInterface $em, UsersRepository $usersRepository, CompaniesRepository $companyRepository, AddressesRepository $addressesRepository, QuotationsRepository $quotationsRepository,  ?Opportunities $opportunities = null): Opportunities
    {
        $opportunitiesId = $opportunitiesData['id'];
        $opportunities = $em->getRepository(Opportunities::class)->find($opportunitiesId);

        if ($opportunities === null) {
            $opportunities = new Opportunities();
            $opportunities->setId($opportunitiesId);
        }



        return $opportunities;
    }

    private function saveOpportunities(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
