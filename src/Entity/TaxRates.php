<?php

namespace App\Entity;

use App\Repository\TaxRatesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxRatesRepository::class)]
class TaxRates
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $rate = null;

    #[ORM\Column]
    private ?bool $for_sales = null;

    #[ORM\Column]
    private ?bool $for_purchases = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $accounting_code_collected = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $accounting_code_deductible = null;

    #[ORM\Column]
    private ?bool $is_expenses_intracommunity_tax_rate = null;

    #[ORM\ManyToOne(inversedBy: 'tax_rates')]
    private ?InvoiceLines $invoiceLines = null;

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

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function isForSales(): ?bool
    {
        return $this->for_sales;
    }

    public function setForSales(bool $for_sales): static
    {
        $this->for_sales = $for_sales;

        return $this;
    }

    public function isForPurchases(): ?bool
    {
        return $this->for_purchases;
    }

    public function setForPurchases(bool $for_purchases): static
    {
        $this->for_purchases = $for_purchases;

        return $this;
    }

    public function getAccountingCodeCollected(): ?string
    {
        return $this->accounting_code_collected;
    }

    public function setAccountingCodeCollected(?string $accounting_code_collected): static
    {
        $this->accounting_code_collected = $accounting_code_collected;

        return $this;
    }

    public function getAccountingCodeDeductible(): ?string
    {
        return $this->accounting_code_deductible;
    }

    public function setAccountingCodeDeductible(?string $accounting_code_deductible): static
    {
        $this->accounting_code_deductible = $accounting_code_deductible;

        return $this;
    }

    public function isIsExpensesIntracommunityTaxRate(): ?bool
    {
        return $this->is_expenses_intracommunity_tax_rate;
    }

    public function setIsExpensesIntracommunityTaxRate(?bool $is_expenses_intracommunity_tax_rate): static
    {
        $this->is_expenses_intracommunity_tax_rate = $is_expenses_intracommunity_tax_rate;

        return $this;
    }

    public function getInvoiceLines(): ?InvoiceLines
    {
        return $this->invoiceLines;
    }

    public function setInvoiceLines(?InvoiceLines $invoiceLines): static
    {
        $this->invoiceLines = $invoiceLines;

        return $this;
    }
}
