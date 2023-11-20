<?php

namespace App\Entity;

use App\Repository\ProjetNatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetNatureRepository::class)]
class ProjetNature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projetNatures')]
    private ?ProjectNatures $projet_nature = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjetNature(): ?ProjectNatures
    {
        return $this->projet_nature;
    }

    public function setProjetNature(?ProjectNatures $projet_nature): static
    {
        $this->projet_nature = $projet_nature;

        return $this;
    }
}
