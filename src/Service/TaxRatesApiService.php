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
use App\Entity\TaxRates; // Remplacement de Contracts par TaxRates
use App\Repository\TaxRatesRepository; // Remplacement de ContractsRepository par TaxRatesRepository

class TaxRatesApiService // Remplacement de ContractsApiService par TaxRatesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, TaxRatesRepository $taxRatesRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/tax-rates', // Remplacement de contracts par tax_rates
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $taxRatesRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, TaxRatesRepository $taxRatesRepository): Response
    {
        $data = $session->get('api_data');

        foreach ($data as $taxRatesData) {
            $em->persist($this->taxRatesToDatabase($taxRatesData, $em));
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $taxRatesIdsInData = array_map(function ($taxRatesData) {
            return $taxRatesData['id'];
        }, $data);

        $allTaxRates = $taxRatesRepository->findAll();
        foreach ($allTaxRates as $taxRates) {
            if (!in_array($taxRates->getId(), $taxRatesIdsInData)) {
                $em->remove($taxRates);
            }
        }

        $this->saveTaxRates($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function taxRatesToDatabase($taxRatesData, EntityManagerInterface $em, ?TaxRates $taxRates = null): TaxRates
    {
        $taxRatesId = $taxRatesData['id'];
        $taxRates = $em->getRepository(TaxRates::class)->find($taxRatesId);

        if ($taxRates === null) {
            $taxRates = new TaxRates();
            $taxRates->setId($taxRatesId);
        }

        $taxRates->setId($taxRatesData['id']);
        $taxRates->setName($taxRatesData['name']);
        $taxRates->setRate($taxRatesData['rate']);
        $taxRates->setForSales($taxRatesData['for_sales']);
        $taxRates->setForPurchases($taxRatesData['for_purchases']);
        $taxRates->setAccountingCodeCollected($taxRatesData['accounting_code_collected']);
        $taxRates->setAccountingCodeDeductible($taxRatesData['accounting_code_deductible']);
        $taxRates->setIsExpensesIntracommunityTaxRate($taxRatesData['is_expenses_intracommunity_tax_rate']);

        return $taxRates;
    }

    private function saveTaxRates(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
