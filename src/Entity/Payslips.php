<?php

namespace App\Entity;

use App\Repository\PayslipsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PayslipsRepository::class)]
class Payslips
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payslips')]
    private ?Workforces $workforce = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column]
    private ?float $net_salary = null;

    #[ORM\Column]
    private ?float $total_cost = null;

    #[ORM\Column]
    private ?float $total_hours = null;

    #[ORM\ManyToOne(inversedBy: 'payslips')]
    private ?Workforces $workforces = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?Workforces $id): static
    {
        $this->id = $id;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getNetSalary(): ?float
    {
        return $this->net_salary;
    }

    public function setNetSalary(float $net_salary): static
    {
        $this->net_salary = $net_salary;

        return $this;
    }

    public function getTotalCost(): ?float
    {
        return $this->total_cost;
    }

    public function setTotalCost(float $total_cost): static
    {
        $this->total_cost = $total_cost;

        return $this;
    }

    public function getTotalHours(): ?float
    {
        return $this->total_hours;
    }

    public function setTotalHours(float $total_hours): static
    {
        $this->total_hours = $total_hours;

        return $this;
    }

    public function getWorkforces(): ?Workforces
    {
        return $this->workforces;
    }

    public function setWorkforces(?Workforces $workforces): static
    {
        $this->workforces = $workforces;

        return $this;
    }
}
