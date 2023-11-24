<?php 
namespace App\Service;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Users;
use App\Repository\UsersRepository;

class UsersApiService
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    
    public function callAPI(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, UsersRepository $usersRepository): Response
    {
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/users',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                ],
            ]
        );

        $data = $response->toArray();
        // dd($data);
        $session->set('api_data', $data);

        $this->dataCheck($session, $em, $logger, $usersRepository);
        
    
        
        return new Response('Received!', Response::HTTP_OK);
    }

    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger, UsersRepository $usersRepository): Response
    {   
        $data = $session->get('api_data');

        foreach ($data as $usersData) {
            $users = $this->usersToDatabase($usersData, $em);
            $em->persist($users);
        }

        // Suppression des entités qui ne sont plus présentes dans les nouvelles données
        $usersIdsInData = array_map(function ($usersData) {
            return $usersData['id'];
        }, $data);

        $allUsers = $usersRepository->findAll();
        foreach ($allUsers as $users) {
            if (!in_array($users->getId(), $usersIdsInData)) {
                $em->remove($users);
            }
        }

        $this->saveUsers($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    private function usersToDatabase($usersData, EntityManagerInterface $em, ?Users $users = null): Users
    {

        $usersId = $usersData['id'];
        $users = $em->getRepository(Users::class)->find($usersId);

        if ($users === null) {
            $users = new Users();
            $users->setId($usersId);
        }

        
        $users->setId($usersData['id']);
        $users->setEmail($usersData['email']);
        $users->setFullName($usersData['full_name']);
        $users->setFirstname($usersData['firstname']);
        $users->setLastname($usersData['lastname']);
        $users->setPhoneNumber($usersData['phone_number']);
        $users->setCellphoneNumber($usersData['cellphone_number']);
        $users->setCompanyNatures($usersData['company_natures']);
        $users->setRoles($usersData['roles']);




        return $users;
    }

    private function saveUsers(EntityManagerInterface $em): void
    {
        $em->flush();
    }
}