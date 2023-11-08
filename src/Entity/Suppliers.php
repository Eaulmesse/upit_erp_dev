<?php

namespace App\Entity;

use App\Repository\SuppliersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuppliersRepository::class)]
class Suppliers
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?float $prefered_tax_rate = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Companies $company = null;

    #[ORM\Column]
    private ?int $thirdparty_code = null;

    #[ORM\OneToMany(mappedBy: 'supplier', targetEntity: Expenses::class)]
    private Collection $expenses;

    #[ORM\OneToMany(mappedBy: 'supplier', targetEntity: SupplierContract::class)]
    private Collection $supplierContracts;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
        $this->supplierContracts = new ArrayCollection();
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

    public function getPreferedTaxRate(): ?float
    {
        return $this->prefered_tax_rate;
    }

    public function setPreferedTaxRate(?float $prefered_tax_rate): static
    {
        $this->prefered_tax_rate = $prefered_tax_rate;

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

    public function getThirdpartyCode(): ?int
    {
        return $this->thirdparty_code;
    }

    public function setThirdpartyCode(int $thirdparty_code): static
    {
        $this->thirdparty_code = $thirdparty_code;

        return $this;
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
            $expense->setSupplier($this);
        }

        return $this;
    }

    public function removeExpense(Expenses $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getSupplier() === $this) {
                $expense->setSupplier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SupplierContract>
     */
    public function getSupplierContracts(): Collection
    {
        return $this->supplierContracts;
    }

    public function addSupplierContract(SupplierContract $supplierContract): static
    {
        if (!$this->supplierContracts->contains($supplierContract)) {
            $this->supplierContracts->add($supplierContract);
            $supplierContract->setSupplier($this);
        }

        return $this;
    }

    public function removeSupplierContract(SupplierContract $supplierContract): static
    {
        if ($this->supplierContracts->removeElement($supplierContract)) {
            // set the owning side to null (unless already changed)
            if ($supplierContract->getSupplier() === $this) {
                $supplierContract->setSupplier(null);
            }
        }

        return $this;
    }
}
