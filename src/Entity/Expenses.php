<?php

namespace App\Entity;

use App\Repository\ExpensesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Nullable;

#[ORM\Entity(repositoryClass: ExpensesRepository::class)]
class Expenses
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_update_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $paid_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expected_payment_date = null;

    #[ORM\Column]
    private ?float $pre_tax_amount = null;

    #[ORM\Column]
    private ?float $tax_amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $left_to_pay = null;

    #[ORM\Column(length: 255)]
    private ?string $currency = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accounting_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accounting_code_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $public_path = null;

    #[ORM\ManyToOne(inversedBy: 'expenses')]
    private ?Suppliers $supplier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $supplier_name = null;

    #[ORM\ManyToOne(inversedBy: 'expenses')]
    private ?Companies $company = null;

    #[ORM\ManyToOne(inversedBy: 'expenses')]
    private ?Workforces $workforce = null;

    #[ORM\ManyToOne(inversedBy: 'expenses')]
    private ?Payslips $payslips = null;

    #[ORM\ManyToOne(inversedBy: 'expenses')]
    private ?Projects $project = null;

    #[ORM\Column]
    private ?float $total_amount = null;

    #[ORM\OneToMany(mappedBy: 'expenses', targetEntity: ExpenseLines::class)]
    private Collection $expense_lines;

    #[ORM\ManyToOne(inversedBy: 'expenses')]
    private ?SupplierContract $supplierContract = null;

    public function __construct()
    {
        $this->expense_lines = new ArrayCollection();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

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

    public function getLastUpdateDate(): ?\DateTimeInterface
    {
        return $this->last_update_date;
    }

    public function setLastUpdateDate(?\DateTimeInterface $last_update_date): static
    {
        $this->last_update_date = $last_update_date;

        return $this;
    }

    public function getPaidDate(): ?\DateTimeInterface
    {
        return $this->paid_date;
    }

    public function setPaidDate(?\DateTimeInterface $paid_date): static
    {
        $this->paid_date = $paid_date;

        return $this;
    }

    public function getExpectedPaymentDate(): ?\DateTimeInterface
    {
        return $this->expected_payment_date;
    }

    public function setExpectedPaymentDate(?\DateTimeInterface $expected_payment_date): static
    {
        $this->expected_payment_date = $expected_payment_date;

        return $this;
    }

    public function getPreTaxAmount(): ?float
    {
        return $this->pre_tax_amount;
    }

    public function setPreTaxAmount(float $pre_tax_amount): static
    {
        $this->pre_tax_amount = $pre_tax_amount;

        return $this;
    }

    public function getTaxAmount(): ?float
    {
        return $this->tax_amount;
    }

    public function setTaxAmount(float $tax_amount): static
    {
        $this->tax_amount = $tax_amount;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->total_amount;
    }

    public function setTotalAmount(float $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getLeftToPay(): ?float
    {
        return $this->left_to_pay;
    }

    public function setLeftToPay(?float $left_to_pay): static
    {
        $this->left_to_pay = $left_to_pay;

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

    public function getAccountingCode(): ?string
    {
        return $this->accounting_code;
    }

    public function setAccountingCode(?string $accounting_code): static
    {
        $this->accounting_code = $accounting_code;

        return $this;
    }

    public function getAccountingCodeName(): ?string
    {
        return $this->accounting_code_name;
    }

    public function setAccountingCodeName(?string $accounting_code_name): static
    {
        $this->accounting_code_name = $accounting_code_name;

        return $this;
    }

    public function getPublicPath(): ?string
    {
        return $this->public_path;
    }

    public function setPublicPath(?string $public_path): static
    {
        $this->public_path = $public_path;

        return $this;
    }

    public function getSupplier(): ?Suppliers
    {
        return $this->supplier;
    }

    public function setSupplier(?Suppliers $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getSupplierName(): ?string
    {
        return $this->supplier_name;
    }

    public function setSupplierName(?string $supplier_name): static
    {
        $this->supplier_name = $supplier_name;

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

    public function getWorkforce(): ?Workforces
    {
        return $this->workforce;
    }

    public function setWorkforce(?Workforces $workforce): static
    {
        $this->workforce = $workforce;

        return $this;
    }

    public function getPayslips(): ?Payslips
    {
        return $this->payslips;
    }

    public function setPayslips(?Payslips $payslips): static
    {
        $this->payslips = $payslips;

        return $this;
    }

    public function getProject(): ?Projects
    {
        return $this->project;
    }

    public function setProject(?Projects $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, ExpenseLines>
     */
    public function getExpenseLines(): Collection
    {
        return $this->expense_lines;
    }

    public function addExpenseLine(ExpenseLines $expenseLine): static
    {
        if (!$this->expense_lines->contains($expenseLine)) {
            $this->expense_lines->add($expenseLine);
            $expenseLine->setExpenses($this);
        }

        return $this;
    }

    public function removeExpenseLine(ExpenseLines $expenseLine): static
    {
        if ($this->expense_lines->removeElement($expenseLine)) {
            // set the owning side to null (unless already changed)
            if ($expenseLine->getExpenses() === $this) {
                $expenseLine->setExpenses(null);
            }
        }

        return $this;
    }

    public function getSupplierContract(): ?SupplierContract
    {
        return $this->supplierContract;
    }

    public function setSupplierContract(?SupplierContract $supplierContract): static
    {
        $this->supplierContract = $supplierContract;

        return $this;
    }

    
}
