<?php

namespace App\Entity;

use App\Repository\CompaniesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
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
    private ?string $thirdparty_code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $intracommunity_number = null;

    #[ORM\Column(nullable: true)]
    private ?string $supplier_thidparty_code = null;

    #[ORM\Column(nullable: true)]
    private ?string $siret = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isB2C = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $language = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Addresses::class)]
    private Collection $addresses;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Employees::class)]
    private Collection $employees;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Quotations::class)]
    private Collection $quotations;


    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Opportunities::class)]
    private Collection $opportunities;

    
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Expenses::class)]
    private Collection $expenses;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Projects::class)]
    private Collection $projects;

    

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->employees = new ArrayCollection();
        // $this->company_name = new ArrayCollection();
        $this->quotations = new ArrayCollection();
        $this->opportunities = new ArrayCollection();
        
        $this->expenses = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

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

    public function setCreationDate(?\DateTimeInterface $creation_date): static
    {
        $this->creation_date = $creation_date;

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

    public function IsSupplier(): ?bool
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

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getThirdpartyCode(): ?string
    {
        return $this->thirdparty_code;
    }

    public function setThirdpartyCode(string $thirdparty_code): static
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

    public function getSupplierThidpartyCode(): ?string
    {
        return $this->supplier_thidparty_code;
    }

    public function setSupplierThidpartyCode(?string $supplier_thidparty_code): static
    {
        $this->supplier_thidparty_code = $supplier_thidparty_code;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
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

    

    /**
     * @return Collection<int, Addresses>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Addresses $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setCompanyId($this);
        }

        return $this;
    }

    public function removeAddress(Addresses $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getCompanyId() === $this) {
                $address->setCompanyId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Employees>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employees $employee): static
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
            $employee->setCompany($this);
        }

        return $this;
    }

    public function removeEmployee(Employees $employee): static
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getCompany() === $this) {
                $employee->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Quotations>
     */
    public function getQuotations(): Collection
    {
        return $this->quotations;
    }

    public function addQuotation(Quotations $quotation): static
    {
        if (!$this->quotations->contains($quotation)) {
            $this->quotations->add($quotation);
            $quotation->setCompany($this);
        }

        return $this;
    }

    public function removeQuotation(Quotations $quotation): static
    {
        if ($this->quotations->removeElement($quotation)) {
            // set the owning side to null (unless already changed)
            if ($quotation->getCompany() === $this) {
                $quotation->setCompany(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, Opportunities>
     */
    public function getOpportunities(): Collection
    {
        return $this->opportunities;
    }

    public function addOpportunity(Opportunities $opportunity): static
    {
        if (!$this->opportunities->contains($opportunity)) {
            $this->opportunities->add($opportunity);
            $opportunity->setCompany($this);
        }

        return $this;
    }

    public function removeOpportunity(Opportunities $opportunity): static
    {
        if ($this->opportunities->removeElement($opportunity)) {
            // set the owning side to null (unless already changed)
            if ($opportunity->getCompany() === $this) {
                $opportunity->setCompany(null);
            }
        }

        return $this;
    }

    public function getInvoiceAddresses(): Collection
    {
        // Utilisez une boucle foreach ou array_filter pour filtrer les adresses
        $invoiceAddresses = $this->addresses->filter(function ($addresses) {
            return $addresses->getIsForInvoice() === true;
        });

        return $invoiceAddresses;
    }

    /**
     * @return Collection<int, Expenses>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expenses $expense): static
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setCompany($this);
        }

        return $this;
    }

    public function removeExpense(Expenses $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getCompany() === $this) {
                $expense->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Projects>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Projects $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setCompany($this);
        }

        return $this;
    }

    public function removeProject(Projects $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getCompany() === $this) {
                $project->setCompany(null);
            }
        }

        return $this;
    }

    


}
