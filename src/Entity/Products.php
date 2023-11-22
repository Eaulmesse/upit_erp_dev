<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $product_code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $supplier_product_code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?float $price_with_tax = null;

    #[ORM\Column(nullable: true)]
    private ?float $tax_rate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $category = null;

    #[ORM\Column(nullable: true)]
    private ?float $job_costing = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $unit = null;

    #[ORM\Column(nullable: true)]
    private ?bool $disabled = null;

    #[ORM\Column(nullable: true)]
    private ?string $internal_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    #[ORM\Column(nullable: true)]
    private ?float $weighted_average_cost = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: QuotationLines::class)]
    private Collection $quotation_lines;

    public function __construct()
    {
        $this->quotation_lines = new ArrayCollection();
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

    public function getProductCode(): ?string
    {
        return $this->product_code;
    }

    public function setProductCode(string $product_code): static
    {
        $this->product_code = $product_code;

        return $this;
    }

    public function getSupplierProductCode(): ?string
    {
        return $this->supplier_product_code;
    }

    public function setSupplierProductCode(string $supplier_product_code): static
    {
        $this->supplier_product_code = $supplier_product_code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getPriceWithTax(): ?float
    {
        return $this->price_with_tax;
    }

    public function setPriceWithTax(?float $price_with_tax): static
    {
        $this->price_with_tax = $price_with_tax;

        return $this;
    }

    public function getTaxRate(): ?float
    {
        return $this->tax_rate;
    }

    public function setTaxRate(float $tax_rate): static
    {
        $this->tax_rate = $tax_rate;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getJobCosting(): ?float
    {
        return $this->job_costing;
    }

    public function setJobCosting(float $job_costing): static
    {
        $this->job_costing = $job_costing;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function isDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getInternalId(): ?string
    {
        return $this->internal_id;
    }

    public function setInternalId(?string $internal_id): static
    {
        $this->internal_id = $internal_id;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getWeightedAverageCost(): ?float
    {
        return $this->weighted_average_cost;
    }

    public function setWeightedAverageCost(float $weighted_average_cost): static
    {
        $this->weighted_average_cost = $weighted_average_cost;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, QuotationLines>
     */
    public function getQuotationLines(): Collection
    {
        return $this->quotation_lines;
    }

    public function addQuotationLine(QuotationLines $quotationLine): static
    {
        if (!$this->quotation_lines->contains($quotationLine)) {
            $this->quotation_lines->add($quotationLine);
            $quotationLine->setProducts($this);
        }

        return $this;
    }

    public function removeQuotationLine(QuotationLines $quotationLine): static
    {
        if ($this->quotation_lines->removeElement($quotationLine)) {
            // set the owning side to null (unless already changed)
            if ($quotationLine->getProducts() === $this) {
                $quotationLine->setProducts(null);
            }
        }

        return $this;
    }


    

}
