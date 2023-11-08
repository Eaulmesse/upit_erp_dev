<?php

namespace App\Entity;

use App\Repository\ExpenseLinesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpenseLinesRepository::class)]
class ExpenseLines
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $total_pre_tax_amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accounting_code = null;

    #[ORM\ManyToOne(inversedBy: 'expense_lines')]
    private ?Expenses $expenses = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotalPreTaxAmount(): ?float
    {
        return $this->total_pre_tax_amount;
    }

    public function setTotalPreTaxAmount(float $total_pre_tax_amount): static
    {
        $this->total_pre_tax_amount = $total_pre_tax_amount;

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

    public function getExpenses(): ?Expenses
    {
        return $this->expenses;
    }

    public function setExpenses(?Expenses $expenses): static
    {
        $this->expenses = $expenses;

        return $this;
    }
}
