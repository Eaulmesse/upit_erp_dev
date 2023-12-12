<?php

namespace App\Entity;

use App\Repository\OpportunitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpportunitiesRepository::class)]
class Opportunities
{
    #[ORM\Id]
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
    private ?Companies $company = null;

    #[ORM\ManyToOne(inversedBy: 'opportunities')]
    private ?Employees $employees = null;

    #[ORM\OneToMany(mappedBy: 'opportunitiy', targetEntity: Quotations::class)]
    private Collection $quotations;

    public function __construct()
    {
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

    public function getCompany(): ?Companies
    {
        return $this->company;
    }

    public function setCompany(?Companies $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getEmployees(): ?Employees
    {
        return $this->employees;
    }

    public function setEmployees(?Employees $employees): static
    {
        $this->employees = $employees;

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
            $quotation->setOpportunitiy($this);
        }

        return $this;
    }

    public function removeQuotation(Quotations $quotation): static
    {
        if ($this->quotations->removeElement($quotation)) {
            // set the owning side to null (unless already changed)
            if ($quotation->getOpportunitiy() === $this) {
                $quotation->setOpportunitiy(null);
            }
        }

        return $this;
    }
}
