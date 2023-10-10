<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectsRepository::class)]
class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Companies $companies = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $estimated_hours = null;

    #[ORM\Column(nullable: true)]
    private ?float $estimated_cost = null;

    #[ORM\Column(nullable: true)]
    private ?float $estimated_revenue = null;

    #[ORM\Column(nullable: true)]
    private ?float $actual_hours = null;

    #[ORM\Column(nullable: true)]
    private ?float $actual_expense_cost = null;

    #[ORM\Column(nullable: true)]
    private ?float $actual_timetrackings_cost = null;

    #[ORM\Column(nullable: true)]
    private ?float $actual_consume_products_cost = null;

    #[ORM\Column(nullable: true)]
    private ?float $actual_revenues = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $estimated_start = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $actual_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $estimated_end = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $actuel_end = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $project_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statues = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $parent_project = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $son_projects = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Workforces $workforce = null;

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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getCompanies(): ?Companies
    {
        return $this->companies;
    }

    public function setCompanies(?Companies $companies): static
    {
        $this->companies = $companies;

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

    public function getEstimatedHours(): ?\DateTimeInterface
    {
        return $this->estimated_hours;
    }

    public function setEstimatedHours(?\DateTimeInterface $estimated_hours): static
    {
        $this->estimated_hours = $estimated_hours;

        return $this;
    }

    public function getEstimatedCost(): ?float
    {
        return $this->estimated_cost;
    }

    public function setEstimatedCost(?float $estimated_cost): static
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

    public function setActualHours(?float $actual_hours): static
    {
        $this->actual_hours = $actual_hours;

        return $this;
    }

    public function getActualExpenseCost(): ?float
    {
        return $this->actual_expense_cost;
    }

    public function setActualExpenseCost(?float $actual_expense_cost): static
    {
        $this->actual_expense_cost = $actual_expense_cost;

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

    public function getActualRevenues(): ?float
    {
        return $this->actual_revenues;
    }

    public function setActualRevenues(?float $actual_revenues): static
    {
        $this->actual_revenues = $actual_revenues;

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

    public function getActualStart(): ?string
    {
        return $this->actual_start;
    }

    public function setActualStart(?string $actual_start): static
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

    public function getActuelEnd(): ?\DateTimeInterface
    {
        return $this->actuel_end;
    }

    public function setActuelEnd(?\DateTimeInterface $actuel_end): static
    {
        $this->actuel_end = $actuel_end;

        return $this;
    }

    public function getProjectName(): ?string
    {
        return $this->project_name;
    }

    public function setProjectName(?string $project_name): static
    {
        $this->project_name = $project_name;

        return $this;
    }

    public function getStatues(): ?string
    {
        return $this->statues;
    }

    public function setStatues(?string $statues): static
    {
        $this->statues = $statues;

        return $this;
    }

    public function getParentProject(): ?string
    {
        return $this->parent_project;
    }

    public function setParentProject(?string $parent_project): static
    {
        $this->parent_project = $parent_project;

        return $this;
    }

    public function getSonProjects(): ?string
    {
        return $this->son_projects;
    }

    public function setSonProjects(?string $son_projects): static
    {
        $this->son_projects = $son_projects;

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
}
