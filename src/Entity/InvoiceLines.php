<?php

namespace App\Entity;

use App\Repository\InvoiceLinesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceLinesRepository::class)]
class InvoiceLines
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoiceLines')]
    private ?Products $product = null;

    #[ORM\OneToMany(mappedBy: 'invoiceLines', targetEntity: TaxRates::class)]
    private Collection $tax_rates;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\Column]
    private ?float $total_pre_tax_amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $total_tax_amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $total_amount = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $chapter = null;

    #[ORM\Column(nullable: true)]
    private ?float $discounts_amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $discounts_amount_with_tax = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $accounting_code = null;

    #[ORM\Column(nullable: true)]
    private ?float $unit_job_costing = null;

    public function __construct()
    {
        $this->tax_rates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): static
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Collection<int, TaxRates>
     */
    public function getTaxRates(): Collection
    {
        return $this->tax_rates;
    }

    public function addTaxRate(TaxRates $taxRate): static
    {
        if (!$this->tax_rates->contains($taxRate)) {
            $this->tax_rates->add($taxRate);
            $taxRate->setInvoiceLines($this);
        }

        return $this;
    }

    public function removeTaxRate(TaxRates $taxRate): static
    {
        if ($this->tax_rates->removeElement($taxRate)) {
            // set the owning side to null (unless already changed)
            if ($taxRate->getInvoiceLines() === $this) {
                $taxRate->setInvoiceLines(null);
            }
        }

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getTotalPreTaxAmount(): ?float
    {
        return $this->total_pre_tax_amount;
    }

    public function setTotalPreTaxAmount(?float $total_pre_tax_amount): static
    {
        $this->total_pre_tax_amount = $total_pre_tax_amount;

        return $this;
    }

    public function getTotalTaxAmount(): ?float
    {
        return $this->total_tax_amount;
    }

    public function setTotalTaxAmount(?float $total_tax_amount): static
    {
        $this->total_tax_amount = $total_tax_amount;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->total_amount;
    }

    public function setTotalAmount(?float $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getChapter(): ?string
    {
        return $this->chapter;
    }

    public function setChapter(?string $chapter): static
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function getDiscountsAmount(): ?float
    {
        return $this->discounts_amount;
    }

    public function setDiscountsAmount(?float $discounts_amount): static
    {
        $this->discounts_amount = $discounts_amount;

        return $this;
    }

    public function getDiscountsAmountWithTax(): ?float
    {
        return $this->discounts_amount_with_tax;
    }

    public function setDiscountsAmountWithTax(?float $discounts_amount_with_tax): static
    {
        $this->discounts_amount_with_tax = $discounts_amount_with_tax;

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

    public function getUnitJobCosting(): ?float
    {
        return $this->unit_job_costing;
    }

    public function setUnitJobCosting(?float $unit_job_costing): static
    {
        $this->unit_job_costing = $unit_job_costing;

        return $this;
    }
}
