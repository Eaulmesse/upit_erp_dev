<?php

namespace App\Entity;

use App\Repository\WorkforcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkforcesRepository::class)]
class Workforces
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birth = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address_street = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address_zip_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address_city = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $job = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $entry_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $exit_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thirdparty_code = null;

    #[ORM\ManyToMany(targetEntity: Projects::class, mappedBy: 'workforces')]
    private Collection $projects;

    #[ORM\OneToMany(mappedBy: 'workforce', targetEntity: Expenses::class)]
    private Collection $expenses;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->expenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    public function setBirth(?\DateTimeInterface $birth): static
    {
        $this->birth = $birth;

        return $this;
    }

    public function getAddressStreet(): ?string
    {
        return $this->address_street;
    }

    public function setAddressStreet(?string $address_street): static
    {
        $this->address_street = $address_street;

        return $this;
    }

    public function getAddressZipCode(): ?string
    {
        return $this->address_zip_code;
    }

    public function setAddressZipCode(?string $address_zip_code): static
    {
        $this->address_zip_code = $address_zip_code;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->address_city;
    }

    public function setAddressCity(?string $address_city): static
    {
        $this->address_city = $address_city;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEntryDate(): ?\DateTimeInterface
    {
        return $this->entry_date;
    }

    public function setEntryDate(?\DateTimeInterface $entry_date): static
    {
        $this->entry_date = $entry_date;

        return $this;
    }

    public function getExitDate(): ?\DateTimeInterface
    {
        return $this->exit_date;
    }

    public function setExitDate(?\DateTimeInterface $exit_date): static
    {
        $this->exit_date = $exit_date;

        return $this;
    }

    public function getThirdpartyCode(): ?string
    {
        return $this->thirdparty_code;
    }

    public function setThirdpartyCode(?string $thirdparty_code): static
    {
        $this->thirdparty_code = $thirdparty_code;

        return $this;
    }


    /**
     * @return Collection<int, Projects>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Projects $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addWorkforce($this);
        }

        return $this;
    }

    public function removeProject(Projects $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeWorkforce($this);
        }

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
            $expense->setWorkforce($this);
        }

        return $this;
    }

    public function removeExpense(Expenses $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getWorkforce() === $this) {
                $expense->setWorkforce(null);
            }
        }

        return $this;
    }

}
