<?php

namespace App\Entity;

use App\Repository\QuotationLinesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuotationLinesRepository::class)]
class QuotationLines
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $product_internal_id = null;

    #[ORM\Column(length: 255)]
    private ?string $product_name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $product_code = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $details = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $unit = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $tax_rates = null;

    #[ORM\Column(length: 255)]
    private ?string $tax_name = null;

    #[ORM\Column]
    private ?float $line_discount_amount = null;

    #[ORM\Column]
    private ?float $line_discount_amount_with_tax = null;

    #[ORM\Column]
    private ?bool $line_discount_unit_is_percent = null;

    #[ORM\Column]
    private ?float $tax_amount = null;

    #[ORM\Column]
    private ?float $pre_tax_amount = null;

    #[ORM\Column]
    private ?float $total_amount = null;

    #[ORM\Column(length: 255)]
    private ?string $margin = null;

    #[ORM\Column]
    private ?int $unit_job_costing = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $chapter = null;

    #[ORM\OneToMany(mappedBy: 'quotationLines', targetEntity: Products::class)]
    private Collection $product;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductInternalId(): ?int
    {
        return $this->product_internal_id;
    }

    public function setProductInternalId(?int $product_internal_id): static
    {
        $this->product_internal_id = $product_internal_id;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): static
    {
        $this->product_name = $product_name;

        return $this;
    }

    public function getProductCode(): ?string
    {
        return $this->product_code;
    }

    public function setProductCode(string $product_code): static
    {
        $this->product_code = $product_code;

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

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getTaxRates(): ?float
    {
        return $this->tax_rates;
    }

    public function setTaxRates(float $tax_rates): static
    {
        $this->tax_rates = $tax_rates;

        return $this;
    }

    public function getTaxName(): ?string
    {
        return $this->tax_name;
    }

    public function setTaxName(string $tax_name): static
    {
        $this->tax_name = $tax_name;

        return $this;
    }

    public function getLineDiscountAmount(): ?float
    {
        return $this->line_discount_amount;
    }

    public function setLineDiscountAmount(float $line_discount_amount): static
    {
        $this->line_discount_amount = $line_discount_amount;

        return $this;
    }

    public function getLineDiscountAmountWithTax(): ?float
    {
        return $this->line_discount_amount_with_tax;
    }

    public function setLineDiscountAmountWithTax(float $line_discount_amount_with_tax): static
    {
        $this->line_discount_amount_with_tax = $line_discount_amount_with_tax;

        return $this;
    }

    public function isLineDiscountUnitIsPercent(): ?bool
    {
        return $this->line_discount_unit_is_percent;
    }

    public function setLineDiscountUnitIsPercent(bool $line_discount_unit_is_percent): static
    {
        $this->line_discount_unit_is_percent = $line_discount_unit_is_percent;

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

    public function getPreTaxAmount(): ?float
    {
        return $this->pre_tax_amount;
    }

    public function setPreTaxAmount(float $pre_tax_amount): static
    {
        $this->pre_tax_amount = $pre_tax_amount;

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

    public function getMargin(): ?string
    {
        return $this->margin;
    }

    public function setMargin(string $margin): static
    {
        $this->margin = $margin;

        return $this;
    }

    public function getUnitJobCosting(): ?int
    {
        return $this->unit_job_costing;
    }

    public function setUnitJobCosting(int $unit_job_costing): static
    {
        $this->unit_job_costing = $unit_job_costing;

        return $this;
    }

    public function getChapter(): ?string
    {
        return $this->chapter;
    }

    public function setChapter(string $chapter): static
    {
        $this->chapter = $chapter;

        return $this;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Products $product): static
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
            $product->setQuotationLines($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): static
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getQuotationLines() === $this) {
                $product->setQuotationLines(null);
            }
        }

        return $this;
    }
}
