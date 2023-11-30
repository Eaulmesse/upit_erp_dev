<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectsRepository::class)]
class Projects
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $estimated_hours = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $estimated_cost = null;

    #[ORM\Column(nullable: true)]
    private ?float $estimated_revenue = null;

    #[ORM\Column]
    private ?float $actual_hours = null;

    #[ORM\Column(nullable: true)]
    private ?float $actual_expenses_cost = null;

    #[ORM\Column(nullable: true)]
    private ?float $actual_timetrackings_cost = null;

    #[ORM\Column(nullable: true)]
    private ?float $actual_consume_products_cost = null;

    #[ORM\Column(nullable: true)]
    private ?float $actual_revenue = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $estimated_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $actual_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $estimated_end = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $actual_end = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Expenses::class)]
    private Collection $expenses;

    

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Quotations::class)]
    private Collection $quotations;

    
    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?ProjectNatures $projectNatures = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Companies $company = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Statuses $statuses = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Users $users = null;


    public function __construct()
    {
        
        $this->workforces = new ArrayCollection();
        $this->expenses = new ArrayCollection();
        $this->quotations = new ArrayCollection();
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

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

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function getEstimatedHours(): ?string
    {
        return $this->estimated_hours;
    }

    public function setEstimatedHours(?string $estimated_hours): static
    {
        $this->estimated_hours = $estimated_hours;

        return $this;
    }

    public function getEstimatedCost(): ?string
    {
        return $this->estimated_cost;
    }

    public function setEstimatedCost(?string $estimated_cost): static
    {
        $this->estimated_cost = $estimated_cost;

        return $this;
    }

    public function getEstimatedRevenue(): ?float
    {
        return $this->estimated_revenue;
    }

    public function setEstimatedRevenue(?float $estimated_revenue): static
    {
        $this->estimated_revenue = $estimated_revenue;

        return $this;
    }

    public function getActualHours(): ?float
    {
        return $this->actual_hours;
    }

    public function setActualHours(float $actual_hours): static
    {
        $this->actual_hours = $actual_hours;

        return $this;
    }

    public function getActualExpensesCost(): ?float
    {
        return $this->actual_expenses_cost;
    }

    public function setActualExpensesCost(?float $actual_expenses_cost): static
    {
        $this->actual_expenses_cost = $actual_expenses_cost;

        return $this;
    }

    public function getActualTimetrackingsCost(): ?float
    {
        return $this->actual_timetrackings_cost;
    }

    public function setActualTimetrackingsCost(?float $actual_timetrackings_cost): static
    {
        $this->actual_timetrackings_cost = $actual_timetrackings_cost;

        return $this;
    }

    public function getActualConsumeProductsCost(): ?float
    {
        return $this->actual_consume_products_cost;
    }

    public function setActualConsumeProductsCost(?float $actual_consume_products_cost): static
    {
        $this->actual_consume_products_cost = $actual_consume_products_cost;

        return $this;
    }

    public function getActualRevenue(): ?float
    {
        return $this->actual_revenue;
    }

    public function setActualRevenue(?float $actual_revenue): static
    {
        $this->actual_revenue = $actual_revenue;

        return $this;
    }

    public function getEstimatedStart(): ?\DateTimeInterface
    {
        return $this->estimated_start;
    }

    public function setEstimatedStart(?\DateTimeInterface $estimated_start): static
    {
        $this->estimated_start = $estimated_start;

        return $this;
    }

    public function getActualStart(): ?\DateTimeInterface
    {
        return $this->actual_start;
    }

    public function setActualStart(?\DateTimeInterface $actual_start): static
    {
        $this->actual_start = $actual_start;

        return $this;
    }

    public function getEstimatedEnd(): ?\DateTimeInterface
    {
        return $this->estimated_end;
    }

    public function setEstimatedEnd(?\DateTimeInterface $estimated_end): static
    {
        $this->estimated_end = $estimated_end;

        return $this;
    }

    public function getActualEnd(): ?\DateTimeInterface
    {
        return $this->actual_end;
    }

    public function setActualEnd(?\DateTimeInterface $actual_end): static
    {
        $this->actual_end = $actual_end;

        return $this;
    }

    public function getProjectNatures(): ?ProjectNatures
    {
        return $this->projectNatures;
    }

    public function setProjectNatures(?ProjectNatures $projectNatures): static
    {
        $this->projectNatures = $projectNatures;

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
            $expense->setProject($this);
        }

        return $this;
    }

    public function removeExpense(Expenses $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getProject() === $this) {
                $expense->setProject(null);
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
            $quotation->setProject($this);
        }

        return $this;
    }

    public function removeQuotation(Quotations $quotation): static
    {
        if ($this->quotations->removeElement($quotation)) {
            // set the owning side to null (unless already changed)
            if ($quotation->getProject() === $this) {
                $quotation->setProject(null);
            }
        }

        return $this;
    }

    public function getStatuses(): ?Statuses
    {
        return $this->statuses;
    }

    public function setStatuses(?Statuses $statuses): static
    {
        $this->statuses = $statuses;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }
}
