<?php

namespace App\Entity;

use App\Repository\LocalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LocalisationRepository::class)]
class Localisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['location:read', 'movement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['location:read', 'movement:read'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['location:read'])]
    private ?string $adresse = null;

    /**
     * @var Collection<int, MouvementStock>
     */
    #[ORM\OneToMany(targetEntity: MouvementStock::class, mappedBy: 'localisation')] // CORRECTION : Typo
    #[Groups(['location:read'])]
    private Collection $mouvementStocks; // CORRECTION : Typo

    public function __construct()
    {
        $this->mouvementStocks = new ArrayCollection(); // CORRECTION : Typo
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return Collection<int, MouvementStock>
     */
    public function getMouvementStocks(): Collection // CORRECTION : Typo
    {
        return $this->mouvementStocks; // CORRECTION : Typo
    }

    public function addMouvementStock(MouvementStock $mouvementStock): static // CORRECTION : Typo
    {
        if (!$this->mouvementStocks->contains($mouvementStock)) { // CORRECTION : Typo
            $this->mouvementStocks->add($mouvementStock); // CORRECTION : Typo
            $mouvementStock->setLocalisation($this);
        }
        return $this;
    }

    public function removeMouvementStock(MouvementStock $mouvementStock): static // CORRECTION : Typo
    {
        if ($this->mouvementStocks->removeElement($mouvementStock)) { // CORRECTION : Typo
            if ($mouvementStock->getLocalisation() === $this) {
                $mouvementStock->setLocalisation(null);
            }
        }
        return $this;
    }
}
