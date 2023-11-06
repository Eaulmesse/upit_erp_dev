<?php

namespace App\Entity;

use App\Repository\ContractsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractsRepository::class)]
class Contracts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\ManyToOne(inversedBy: 'contracts')]
    private ?Users $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expected_delivery_date = null;

    #[ORM\Column(length: 255)]
    private ?string $company_name = null;

    #[ORM\Column(length: 255)]
    private ?string $contact_name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $street = null;

    #[ORM\Column]
    private ?int $zip_code = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $city = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $country = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $first_invoice_planned_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $generate_and_send_recurring_invoices = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $invoice_frenquency_in_months = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $preauthorized_debit = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_update_date = null;

    #[ORM\Column(length: 255)]
    private ?string $invoice_address_street = null;

    #[ORM\Column(length: 255)]
    private ?string $invoice_address_city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $delivery_address_street = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $delivery_address_city = null;

    #[ORM\ManyToOne(inversedBy: 'contracts')]
    private ?Companies $company = null;

    // #[ORM\Column(type: Types::TEXT, nullable: true)]
    // private ?string $project = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?quotations $quotation = null;

    #[ORM\OneToMany(mappedBy: 'contract', targetEntity: Invoices::class)]
    private Collection $project;

    #[ORM\OneToMany(mappedBy: 'contracts', targetEntity: Invoices::class)]
    private Collection $invoices;

    public function __construct()
    {
        $this->project = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

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

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getExpectedDeliveryDate(): ?\DateTimeInterface
    {
        return $this->expected_delivery_date;
    }

    public function setExpectedDeliveryDate(?\DateTimeInterface $expected_delivery_date): static
    {
        $this->expected_delivery_date = $expected_delivery_date;

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

    public function getContactName(): ?string
    {
        return $this->contact_name;
    }

    public function setContactName(string $contact_name): static
    {
        $this->contact_name = $contact_name;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zip_code;
    }

    public function setZipCode(int $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getFirstInvoicePlannedDate(): ?\DateTimeInterface
    {
        return $this->first_invoice_planned_date;
    }

    public function setFirstInvoicePlannedDate(?\DateTimeInterface $first_invoice_planned_date): static
    {
        $this->first_invoice_planned_date = $first_invoice_planned_date;

        return $this;
    }

    public function getGenerateAndSendRecurringInvoices(): ?string
    {
        return $this->generate_and_send_recurring_invoices;
    }

    public function setGenerateAndSendRecurringInvoices(?string $generate_and_send_recurring_invoices): static
    {
        $this->generate_and_send_recurring_invoices = $generate_and_send_recurring_invoices;

        return $this;
    }

    public function getInvoiceFrenquencyInMonths(): ?string
    {
        return $this->invoice_frenquency_in_months;
    }

    public function setInvoiceFrenquencyInMonths(?string $invoice_frenquency_in_months): static
    {
        $this->invoice_frenquency_in_months = $invoice_frenquency_in_months;

        return $this;
    }

    public function getPreauthorizedDebit(): ?string
    {
        return $this->preauthorized_debit;
    }

    public function setPreauthorizedDebit(?string $preauthorized_debit): static
    {
        $this->preauthorized_debit = $preauthorized_debit;

        return $this;
    }

    public function getCompany(): ?Companies
    {
        return $this->company;
    }

    public function setCompany(?Companies $company): static
    {
        $this->company = $company;

        return $this;
    }

    // public function getProject(): ?string
    // {
    //     return $this->project;
    // }

    // public function setProject(?string $project): static
    // {
    //     $this->project = $project;

    //     return $this;
    // }

    public function getQuotation(): ?quotations
    {
        return $this->quotation;
    }

    public function setQuotation(?quotations $quotation): static
    {
        $this->quotation = $quotation;

        return $this;
    }

    public function getLastUpdateDate(): ?\DateTimeInterface
    {
        return $this->last_update_date;
    }

    public function setLastUpdateDate(?\DateTimeInterface $last_update_date): static
    {
        $this->last_update_date = $last_update_date;

        return $this;
    }

    public function getInvoiceAddressStreet(): ?string
    {
        return $this->invoice_address_street;
    }

    public function setInvoiceAddressStreet(string $invoice_address_street): static
    {
        $this->invoice_address_street = $invoice_address_street;

        return $this;
    }

    public function getInvoiceAddressCity(): ?string
    {
        return $this->invoice_address_city;
    }

    public function setInvoiceAddressCity(string $invoice_address_city): static
    {
        $this->invoice_address_city = $invoice_address_city;

        return $this;
    }

    public function getDeliveryAddressStreet(): ?string
    {
        return $this->delivery_address_street;
    }

    public function setDeliveryAddressStreet(?string $delivery_address_street): static
    {
        $this->delivery_address_street = $delivery_address_street;

        return $this;
    }

    public function getDeliveryAddressCity(): ?string
    {
        return $this->delivery_address_city;
    }

    public function setDeliveryAddressCity(?string $delivery_address_city): static
    {
        $this->delivery_address_city = $delivery_address_city;

        return $this;
    }

    /**
     * @return Collection<int, Invoices>
     */
    public function getProject(): Collection
    {
        return $this->project;
    }

    public function addProject(Invoices $project): static
    {
        if (!$this->project->contains($project)) {
            $this->project->add($project);
            $project->setContract($this);
        }

        return $this;
    }

    public function removeProject(Invoices $project): static
    {
        if ($this->project->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getContract() === $this) {
                $project->setContract(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invoices>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoices $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setContracts($this);
        }

        return $this;
    }

    public function removeInvoice(Invoices $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getContracts() === $this) {
                $invoice->setContracts(null);
            }
        }

        return $this;
    }
}
