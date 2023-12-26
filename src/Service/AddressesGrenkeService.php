<?php 
namespace App\Service;


use App\Entity\Addresses;


use App\Repository\CompaniesRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class AddressesGrenkeService 
{
    private $client;


    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getResponse($response, CompaniesRepository $companiesRepository, EntityManagerInterface $em)
    {
        
        $this->postGrenke($response);
        $responseAPI = $this->callAPI($response);
        $grenkeAddresse = $this->getGrenke($responseAPI);
        $this->mapGrenke($grenkeAddresse, $companiesRepository, $em);
        
    }

    public function postGrenke($response): void {
        
        $data = [
            "name" => "Grenke",
            "company_id" => $response['data']['id'],
            "contact_name" => true,
            "company_name" => $response['data']['name'],
            "address_street" => "54 Rue Marcel Dassault",
            "address_zip_code" => "69740",
            "address_city" => "Genas",
            "address_country" => "France",
            "is_for_invoice" => true,
            "is_for_delivery" => false,
            "is_for_quotation" => false
        ];

        
        $jsonData = json_encode($data);
        $client = HttpClient::create();

        $response = $client->request('POST', 'https://axonaut.com/api/v2/addresses', [
            'headers' => [
                'userApiKey' => $_ENV['API_KEY'],
                'Content-Type' => 'application/json',
            ],
            'body' => $jsonData
        ]);
    }

    public function callAPI($response): array {
        
        $url = 'https://axonaut.com/api/v2/companies/{companyId}/addresses';
        $finalUrl = str_replace('{companyId}', $response['data']['id'], $url);

        $responseAPI = $this->client->request('GET', $finalUrl, [
            'headers' => [
                'userApiKey' => $_ENV['API_KEY'],
            ]
        ]);

        return $responseAPI->ToArray();
    }

    public function getGrenke($responseAPI): array{

        foreach($responseAPI as $addresse) {
            if($addresse['name'] == "Grenke") {
                return $grenkeAddresse = $addresse;
            }
        }

    }

    public function mapGrenke($grenkeAddresse, CompaniesRepository $companiesRepository, EntityManagerInterface $em): void {

        $addresse = new Addresses();

        $addresse->setId($grenkeAddresse['id']);

        $addresse->setName($grenkeAddresse['name']);
        $addresse->setId($grenkeAddresse['id']);
        $addresse->setName($grenkeAddresse['name']);
        $addresse->setContactName($grenkeAddresse['contact_name']);
        $addresse->setCompanyName($grenkeAddresse['company_name']);
        $addresse->setAddressStreet($grenkeAddresse['address_street']);
        $addresse->setAddressZipCode($grenkeAddresse['address_zip_code']);
        $addresse->setAddressCity($grenkeAddresse['address_city']);
        $addresse->setAddressRegion($grenkeAddresse['address_region']);
        $addresse->setAddressCountry($grenkeAddresse['address_country']);
        $addresse->setIsForInvoice($grenkeAddresse['is_for_invoice']);
        $addresse->setIsForDelivery($grenkeAddresse['is_for_delivery']);
        $addresse->setIsForQuotation($grenkeAddresse['is_for_quotation']);
        $addresse->setIsForDelivery($grenkeAddresse['is_for_delivery']);

        $company = $companiesRepository->find($grenkeAddresse['company']['id']);
        $addresse->setCompanyId($company);

        $em->persist($addresse);
        $em->flush();

    }


    

    

}