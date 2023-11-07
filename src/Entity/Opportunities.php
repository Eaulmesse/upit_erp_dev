<?php

namespace App\Entity;

use App\Repository\OpportunitiesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpportunitiesRepository::class)]
class Opportunities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column]
    private ?float $probability = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column]
    private ?bool $is_win = null;

    #[ORM\Column]
    private ?bool $is_archived = null;

    #[ORM\Column(length: 255)]
    private ?string $user_name = null;

    #[ORM\Column(length: 255)]
    private ?string $pip_name = null;

    #[ORM\Column(length: 255)]
    private ?string $pip_step_name = null;

    #[ORM\ManyToOne(inversedBy: 'opportunities')]
    private ?companies $company = null;

    #[ORM\Column(length: 255)]
    private ?string $company_name = null;

    #[ORM\Column]
    private ?bool $company_is_supplier = null;

    #[ORM\Column]
    private ?bool $company_is_prospect = null;

    #[ORM\Column]
    private ?bool $company_is_customer = null;

    #[ORM\ManyToOne(inversedBy: 'opportunities')]
    private ?employees $employees = null;

    #[ORM\Column(length: 255)]
    private ?string $employee_name = null;

    #[ORM\Column(length: 255)]
    private ?string $employee_email = null;

    #[ORM\Column(length: 255)]
    private ?string $employee_cellphone_number = null;

    #[ORM\Column(length: 255)]
    private ?string $employee_phone_number = null;

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

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getProbability(): ?float
    {
        return $this->probability;
    }

    public function setProbability(float $probability): static
    {
        $this->probability = $probability;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): static
    {
        $this->due_date = $due_date;

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

    public function isIsWin(): ?bool
    {
        return $this->is_win;
    }

    public function setIsWin(bool $is_win): static
    {
        $this->is_win = $is_win;

        return $this;
    }

    public function isIsArchived(): ?bool
    {
        return $this->is_archived;
    }

    public function setIsArchived(bool $is_archived): static
    {
        $this->is_archived = $is_archived;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): static
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getPipName(): ?string
    {
        return $this->pip_name;
    }

    public function setPipName(string $pip_name): static
    {
        $this->pip_name = $pip_name;

        return $this;
    }

    public function getPipStepName(): ?string
    {
        return $this->pip_step_name;
    }

    public function setPipStepName(string $pip_step_name): static
    {
        $this->pip_step_name = $pip_step_name;

        return $this;
    }

    public function getCompany(): ?companies
    {
        return $this->company;
    }

    public function setCompany(?companies $company): static
    {
        $this->company = $company;

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

    public function isCompanyIsSupplier(): ?bool
    {
        return $this->company_is_supplier;
    }

    public function setCompanyIsSupplier(bool $company_is_supplier): static
    {
        $this->company_is_supplier = $company_is_supplier;

        return $this;
    }

    public function isCompanyIsProspect(): ?bool
    {
        return $this->company_is_prospect;
    }

    public function setCompanyIsProspect(bool $company_is_prospect): static
    {
        $this->company_is_prospect = $company_is_prospect;

        return $this;
    }

    public function isCompanyIsCustomer(): ?bool
    {
        return $this->company_is_customer;
    }

    public function setCompanyIsCustomer(bool $company_is_customer): static
    {
        $this->company_is_customer = $company_is_customer;

        return $this;
    }

    public function getEmployees(): ?employees
    {
        return $this->employees;
    }

    public function setEmployees(?employees $employees): static
    {
        $this->employees = $employees;

        return $this;
    }

    public function getEmployeeName(): ?string
    {
        return $this->employee_name;
    }

    public function setEmployeeName(string $employee_name): static
    {
        $this->employee_name = $employee_name;

        return $this;
    }

    public function getEmployeeEmail(): ?string
    {
        return $this->employee_email;
    }

    public function setEmployeeEmail(string $employee_email): static
    {
        $this->employee_email = $employee_email;

        return $this;
    }

    public function getEmployeeCellphoneNumber(): ?string
    {
        return $this->employee_cellphone_number;
    }

    public function setEmployeeCellphoneNumber(string $employee_cellphone_number): static
    {
        $this->employee_cellphone_number = $employee_cellphone_number;

        return $this;
    }

    public function getEmployeePhoneNumber(): ?string
    {
        return $this->employee_phone_number;
    }

    public function setEmployeePhoneNumber(string $employee_phone_number): static
    {
        $this->employee_phone_number = $employee_phone_number;

        return $this;
    }
}