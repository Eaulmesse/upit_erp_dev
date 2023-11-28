<?php

namespace App\Service;

use App\Entity\ExpenseLines;
use Psr\Log\LoggerInterface;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ExpenseLinesRepository;
use App\Repository\ExpensesRepository;

class ExpenseLinesApiService // Remplacement de ExpensesLinesApiService par ExpenseLinesApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function getData(SessionInterface $session, EntityManagerInterface $em, $expensesData, $expenseLines, ExpenseLinesRepository $expenseLinesRepository, ExpensesRepository $expensesRepository): Response
    {
        
        
        
        
        $session->set('lines_data', $expenseLines);
        $session->set('expense_data', $expensesData);

        $this->dataCheck($session, $em, $expenseLinesRepository, $expensesRepository);

        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, ExpenseLinesRepository $expenseLinesRepository, ExpensesRepository $expensesRepository): Response
    {
        $expenseData = $session->get("expense_data");

        $this->expenseLinesToDatabase($expenseData, $em, $expensesRepository);

        $this->saveExpenseLines($em);

        return new Response('Received!', Response::HTTP_OK);
    }

    private function expenseLinesToDatabase($expenseData, EntityManagerInterface $em, ExpensesRepository $expensesRepository, ?ExpenseLines $expenseLines = null): void
    {
        $expenseLinesData = $expenseData["expense_lines"];

        foreach ($expenseLinesData as $lines) {
            $expenseLines = new ExpenseLines(); // Créez un nouvel objet à chaque itération

            $expenseLines->setTitle($lines['title']);
            $expenseLines->setQuantity($lines['quantity']);
            $expenseLines->setTotalPreTaxAmount($lines['total_pre_tax_amount']);
            $expenseLines->setAccountingCode($lines['accounting_code']);

            $expenseLines->setExpenses($expensesRepository->find($expenseData['id']));

            $em->persist($expenseLines);
        }
    }

    private function saveExpenseLines(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}
