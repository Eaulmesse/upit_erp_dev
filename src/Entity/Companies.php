<?php

namespace App\Entity;

use App\Repository\CompaniesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompaniesRepository::class)]
class Companies
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address_street = null;

    #[ORM\Column(nullable: true)]
    private ?int $address_zip_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address_city = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address_region = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address_country = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_supplier = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_prospect = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_customer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $currency = null;

    #[ORM\Column(nullable: true)]
    private ?int $thirdparty_code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $intracommunity_number = null;

    #[ORM\Column(nullable: true)]
    private ?int $supplier_thidparty_code = null;

    #[ORM\Column(nullable: true)]
    private ?int $siret = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isB2C = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $language = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getAddressStreet(): ?string
    {
        return $this->address_street;
    }

    public function setAddressStreet(string $adress_street): static
    {
        $this->address_street = $adress_street;

        return $this;
    }

    public function getAddressZipCode(): ?int
    {
        return $this->address_zip_code;
    }

    public function setAddressZipCode(int $address_zip_code): static
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

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function isIsSupplier(): ?bool
    {
        return $this->is_supplier;
    }

    public function setIsSupplier(bool $is_supplier): static
    {
        $this->is_supplier = $is_supplier;

        return $this;
    }

    public function isIsProspect(): ?bool
    {
        return $this->is_prospect;
    }

    public function setIsProspect(bool $is_prospect): static
    {
        $this->is_prospect = $is_prospect;

        return $this;
    }

    public function isIsCustomer(): ?bool
    {
        return $this->is_customer;
    }

    public function setIsCustomer(bool $is_customer): static
    {
        $this->is_customer = $is_customer;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getThirdpartyCode(): ?int
    {
        return $this->thirdparty_code;
    }

    public function setThirdpartyCode(int $thirdparty_code): static
    {
        $this->thirdparty_code = $thirdparty_code;

        return $this;
    }

    public function getIntracommunityNumber(): ?string
    {
        return $this->intracommunity_number;
    }

    public function setIntracommunityNumber(string $intracommunity_number): static
    {
        $this->intracommunity_number = $intracommunity_number;

        return $this;
    }

    public function getSupplierThidpartyCode(): ?int
    {
        return $this->supplier_thidparty_code;
    }

    public function setSupplierThidpartyCode(?int $supplier_thidparty_code): static
    {
        $this->supplier_thidparty_code = $supplier_thidparty_code;

        return $this;
    }

    public function getSiret(): ?int
    {
        return $this->siret;
    }

    public function setSiret(int $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function isIsB2C(): ?bool
    {
        return $this->isB2C;
    }

    public function setIsB2C(bool $isB2C): static
    {
        $this->isB2C = $isB2C;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }
}
