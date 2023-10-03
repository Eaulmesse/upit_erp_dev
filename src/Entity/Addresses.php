<?php

namespace App\Entity;

use App\Repository\AddressesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressesRepository::class)]
class Addresses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact_name = null;

    #[ORM\Column(length: 255)]
    private ?string $company_name = null;

    #[ORM\Column(length: 255)]
    private ?string $address_street = null;

    #[ORM\Column]
    private ?string $address_zip_code = null;

    #[ORM\Column(length: 255)]
    private ?string $address_city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address_region = null;

    #[ORM\Column(length: 255)]
    private ?string $address_country = null;

    #[ORM\Column]
    private ?bool $is_for_invoice = null;

    #[ORM\Column]
    private ?bool $is_for_delivery = null;

    #[ORM\Column]
    private ?bool $is_for_quotation = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private ?Companies $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->contact_name;
    }

    public function setContactName(?string $contact_name): static
    {
        $this->contact_name = $contact_name;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): static
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getAddressStreet(): ?string
    {
        return $this->address_street;
    }

    public function setAddressStreet(string $address_street): static
    {
        $this->address_street = $address_street;

        return $this;
    }

    public function getAddressZipCode(): ?string
    {
        return $this->address_zip_code;
    }

    public function setAddressZipCode(string $address_zip_code): static
    {
        $this->address_zip_code = $address_zip_code;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->address_city;
    }

    public function setAddressCity(string $address_city): static
    {
        $this->address_city = $address_city;

        return $this;
    }

    public function getAddressRegion(): ?string
    {
        return $this->address_region;
    }

    public function setAddressRegion(?string $address_region): static
    {
        $this->address_region = $address_region;

        return $this;
    }

    public function getAddressCountry(): ?string
    {
        return $this->address_country;
    }

    public function setAddressCountry(string $address_country): static
    {
        $this->address_country = $address_country;

        return $this;
    }

    public function isIsForInvoice(): ?bool
    {
        return $this->is_for_invoice;
    }

    public function setIsForInvoice(bool $is_for_invoice): static
    {
        $this->is_for_invoice = $is_for_invoice;

        return $this;
    }

    public function isIsForDelivery(): ?bool
    {
        return $this->is_for_delivery;
    }

    public function setIsForDelivery(bool $is_for_delivery): static
    {
        $this->is_for_delivery = $is_for_delivery;

        return $this;
    }

    public function isIsForQuotation(): ?bool
    {
        return $this->is_for_quotation;
    }

    public function setIsForQuotation(bool $is_for_quotation): static
    {
        $this->is_for_quotation = $is_for_quotation;

        return $this;
    }

    public function getCompanyId(): ?Companies
    {
        return $this->company;
    }

    public function setCompanyId(?Companies $company): static
    {
        $this->company = $company;

        return $this;
    }
}
