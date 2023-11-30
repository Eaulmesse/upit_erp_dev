<?php

namespace App\Service;

use Amp\Http\Client\Response;
use DateTime;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ApiService
{
//     private $httpClient;
//     private $logger;

//     public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
//     {
//         $this->httpClient = $httpClient;
//         $this->logger = $logger;
//     }

//     public function fetchData(array $config)
//     {
//         $response = $this->httpClient->request('GET', $config['url'], [
//             'headers' => ['userApiKey' => $_ENV['API_KEY']]
//         ]);

        
//         if ($response->getStatusCode() === 200) {

//             return json_decode($response->getContent(), true);
//         } else {

//             throw new \Exception('Échec de la récupération des données depuis l\'API');
//         }
//     }

//     public function dataCheck($data, $className, $object, $repository, EntityManagerInterface $em)
//     {   

//         foreach($data as $line) {

//             $inDatabaseId = $repository->find($line['id']);
            
//             if ($inDatabaseId == null) {
//                 $inDatabaseId = $this->MapToDatabase($repository, $className, $object,  $line, $em);
//                 $em->persist($inDatabaseId);
//             }   
//         }

//         $IdsInData = array_map(function($line) {
//             return $line['id'];
//         }, $data);

//         $allObjets = $repository->findall();
//         foreach ($allObjets as $line) {
//             if (!in_array($line->getId(), $IdsInData)) {
//                 $em->remove($line);
//             }
//         }

        
        
//     }

//     public function MapToDatabase($repository, $className, $object,  $line, EntityManagerInterface $em): object
//     { 
//         $metadata = $em->getClassMetadata('App\Entity\\' . ucfirst($className));
//         $classRepository = $em->getRepository('App\Entity\\' . ucfirst($className));
        
//         $class = new \ReflectionClass($metadata->getName());
//         $entity = $class->newInstance();

//         $arrayValue = array();
//         foreach($line as $value)
//         {
//             array_push($arrayValue, $value);
//         }
        
//         $i = 0;
//         foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
//             if(str_contains($method->name, "set"))
//             {   
//                 try {

                    
//                     // Création des méthodes
//                     $methodName = $method->getParameters()[0]->name;
//                     $methodType = $class->getProperty($methodName)->getType()->getName();
//                     $methodValue = "";


//                     $setter = $method->name;
                
                
//                     switch ($methodType) {
//                         case 'DateTimeInterface':
    
//                             $entity->$setter(new \DateTime($arrayValue[$i]));
//                             break;
    
//                         case str_contains($methodType, "Entity"):
    
//                             $methodValue = $arrayValue[$i] ?? null;
//                             if ($methodValue !== null) {
//                             $joinRepository = $em->getRepository($methodType);
//                             $methodValue = $joinRepository->find($methodValue);
//                             }
//                             break;
                        
//                         default:
//                             $entity->$setter($arrayValue[$i]);
//                             break;
//                     }
//                     $i++;
//                 }
//                 catch (\Exception $e) {
//                     dd($e);
//                 }
                
//             }
//          }  

        
//         return $entity;
//     }    

//     public function saveEntities(EntityManagerInterface $em): void
//     {
//         $em->flush();
//     }

        
        
       

       
   

}


