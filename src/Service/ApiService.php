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
    private $httpClient;
    private $logger;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    public function fetchData(array $config)
    {
        $response = $this->httpClient->request('GET', $config['url'], [
            'headers' => ['userApiKey' => $_ENV['API_KEY']]
        ]);

        
        if ($response->getStatusCode() === 200) {

            return json_decode($response->getContent(), true);
        } else {

            throw new \Exception('Échec de la récupération des données depuis l\'API');
        }
    }

    public function dataCheck($data, $className, $object, $repository, EntityManagerInterface $em)
    {   

        foreach($data as $line) {

            $inDatabaseId = $repository->find($line['id']);
            
            if ($inDatabaseId == null) {
                $inDatabaseId = $this->MapToDatabase($repository, $className, $object,  $line, $em);
                // $em->persist($inDatabaseId);
            }   
            else {
                $inDatabaseId = $this->MapToDatabase($repository, $className, $object,  $line, $em);
            } 
        }

        $IdsInData = array_map(function($line) {
            return $line['id'];
        }, $data);

        $allObjets = $repository->findall();
        foreach ($allObjets as $line) {
            if (!in_array($line->getId(), $IdsInData)) {
                $em->remove($line);
            }
        }

        
        
    }

    public function MapToDatabase($repository, $className, $object,  $line, EntityManagerInterface $em): object
    { 
        $metadata = $em->getClassMetadata(trim('App\Entity\ ') . ucfirst($className));
        $class = new \ReflectionClass($metadata->getName());
        $entity = $class->newInstance();

        $arrayValue = array();
        foreach($line as $value)
        {
            array_push($arrayValue, $value);
        }
        
        $i = 0;
        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if(str_contains($method->name, "set") || str_contains($method->name, "add"))
            {
                
                $methodName = $method->getParameters()[0]->name;
                
                
                $methodType = $class->getProperty($methodName)->getType()->getName();
             
                $methodValue = "";
                
                
                
                switch ($methodType) {
                    
                    case 'int':
                        if($arrayValue[$i] !== null)
                        {
                            $methodValue = (int) $arrayValue[$i];
                        }
                        else 
                        {
                            $methodValue = null;
                        }
                        
                        break;
                    case 'float':
                        $methodValue = (float) $arrayValue[$i];
                        break;
                    case 'bool':
                        $methodValue = (bool) $arrayValue[$i];
                        break;
                    case 'string':

                       
                        $methodValue = (string) $arrayValue[$i];

                        break;
                    case 'DateTimeInterface':
                        
                        if($arrayValue[$i] !== null)
                        {
                            $format1 = 'Y-m-d\TH:i:sP';
                            $format2 = 'Y-m-d'; 

                            $datetime1 = DateTime::createFromFormat($format1, $arrayValue[$i]);
                            $datetime2 = DateTime::createFromFormat($format2, $arrayValue[$i]);
                            
                            if ($datetime1 !== false ) {
                                $methodValue = $datetime1;
                            } elseif ($datetime2 !== false) {
                                $methodValue = $datetime2;
                            }
                        }
                        else 
                        {
                            $methodValue = null;
                        }
                        break;
                    case str_contains($methodType, 'App\Entity', ):

                        if($arrayValue[$i] !== null)
                        {
                            $joinRepository = $em->getRepository($methodType);

                            $methodValue = $joinRepository->find($arrayValue[$i]);
                        }
                        else 
                        {
                            $methodValue = null;
                        }
                        break;   
                    
                    case str_contains($methodType, 'Doctrine\Common\Collections\Collection'):

                        $arrayRepository = trim('App\Entity\ ') . ucfirst($methodName);
                        $joinRepository = $em->getRepository($arrayRepository);
                        $metadata = $em->getClassMetadata($arrayRepository);

                        
                        $class = new \ReflectionClass($metadata->getName());
                        $entity = $class->newInstance();

                        foreach ($arrayValue[$i] as $key => $arrays) {
                            foreach($arrays as $array) {
                                $arrayRepository = trim('App\Entity\ ') . ucfirst($methodName);
                                $joinRepository = $em->getRepository($arrayRepository);
                                $metadata = $em->getClassMetadata($arrayRepository);

                                $class = new \ReflectionClass($metadata->getName());
                                $entity = $class->newInstance();
                                
                                
                                $setter = 'set' . ucfirst($value);
                                
                                if (method_exists($entity, $setter)) {
                                    $entity->$setter($value);
                                }
                                dd($entity);
                            }

                            
                        }

    

                        

                        $em->persist($entity);
                        $em->flush();
                        break;
                    }

                $method->invoke($entity, $methodValue);
                
                    
                $i++;
            }
            
                    
                    
                
                
                
                
            
        }   
        // dd($entity);
        return $entity;
    }    

    public function saveEntities(EntityManagerInterface $em): void
    {
        $em->flush();
    }

        
        
       

       
   

}


// foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
//     if (strpos($method->name, 'set') === 0) {


        
//         print_r("   " . $arrayValue[$i] . "   ");
//         $i++;
//         $propertyName = lcfirst(substr($method->name, 3));
//         $propertyName = lcfirst(str_replace('_', '', ucwords($propertyName, '_')));

//         if (property_exists($class->getName(), $propertyName)) {

//             $propertyType = $class->getProperty($propertyName)->getType()->getName();
//             $propertyValue = 'valeur pour ' . $propertyName;

//             if (isset($arrayValue[$i])) {
//                 switch ($propertyType) {
//                     case 'int':
//                         $propertyValue = (int) $arrayValue[$i];
//                         break;
        //             case 'float':
        //                 $propertyValue = (float) $arrayValue[$i];
        //                 break;
        //             case 'bool':
        //                 $propertyValue = (bool) $arrayValue[$i];
        //                 break;
        //             case 'string':
        //                 $propertyValue = (string) $arrayValue[$i];
        //                 break;
        //             case 'DateTimeInterface':
        //                 $propertyValue = \DateTime::createFromFormat('Y-m-d', $arrayValue[$i]);
        //                 break;
                // }
        //     } else {
        //         // Si l'index n'existe pas, attribuez une valeur par défaut
        //         $propertyValue = null;
        //     }
            
            
        //     $method->invoke($entity, $propertyValue);
            // $i++;
            
            
//         }
        
        
        
//     } 
// }   
// dd($entity);
// return $entity;
// }    