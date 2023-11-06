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
use App\Entity\Users;

class UsersController extends AbstractController
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $webhookLogger, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->logger = $webhookLogger;
    }

    #[Route('/users/get', name: 'app_get_users')]
    public function callAPI(Request $request, SessionInterface $session, EntityManagerInterface $em, UsersRepository $usersRepository, LoggerInterface $logger): Response
    {
 
        $response = $this->client->request(
            'GET',
            'https://axonaut.com/api/v2/users',
            [
                'headers' => [
                    'userApiKey' => $_ENV['API_KEY'],
                    // Ajoutez d'autres en-têtes si nécessaire
                ]
            ]
        );

        $data = $response->toArray();
        $session->set('api_data', $data);
        // dd($data);
        $this->dataCheck($session, $em, $usersRepository, $logger);

        return new Response('Received!', Response::HTTP_OK);
    }
    
    public function dataCheck(SessionInterface $session, EntityManagerInterface $em, UsersRepository $usersRepository, LoggerInterface $logger): Response
    {   
        $data = $session->get('api_data');

        foreach($data as $userData) {

            $inDatabaseId = $usersRepository->find($userData['id']);

            if ($inDatabaseId == null) {
                
                $inDatabaseId = $this->userToDatabase($userData, $em);
                $em->persist($inDatabaseId);
            }   
            else {
                $inDatabaseId = $this->userToDatabase($userData, $em);
            } 
        }

        $userIdsInData = array_map(function($userData) {
            return $userData['id'];
        }, $data);

        $allUsers = $usersRepository->findall();
        foreach ($allUsers as $user) {
            if (!in_array($user->getId(), $userIdsInData)) {
                $em->remove($user);
            }
        }

        $this->saveUsers($em);
        
        return new Response('Received!', Response::HTTP_OK);
    }

    

    private function userToDatabase($userData, EntityManagerInterface $em, ?Users $user = null): Users
    {
        $user = New Users;

        $user->setId($userData['id']);
        $user->setEmail($userData['email']);
        $user->setFullName($userData['full_name']);
        $user->setFirstname($userData['firstname']);
        $user->setLastname($userData['lastname']);
        $user->setPhoneNumber($userData['phone_number']);
        $user->setCellphoneNumber($userData['cellphone_number']);
        $user->setCompanyNatures($userData['company_natures']);
        $user->setRoles($userData['roles']);


        return $user;
    }

    private function saveUsers(EntityManagerInterface $em): void
    {
        $em->flush();
    }


    
}
