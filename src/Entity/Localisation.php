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
     * @var Collection<int, MouvmentStock>
     */
    #[ORM\OneToMany(targetEntity: MouvmentStock::class, mappedBy: 'localisation')]
    #[Groups(['location:read'])] // <-- L'ANNOTATION QUI MANQUAIT
    private Collection $mouvmentStocks;

    public function __construct()
    {
        $this->mouvmentStocks = new ArrayCollection();
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
     * @return Collection<int, MouvmentStock>
     */
    public function getMouvmentStocks(): Collection
    {
        return $this->mouvmentStocks;
    }

    public function addMouvmentStock(MouvmentStock $mouvmentStock): static
    {
        if (!$this->mouvmentStocks->contains($mouvmentStock)) {
            $this->mouvmentStocks->add($mouvmentStock);
            $mouvmentStock->setLocalisation($this);
        }

        return $this;
    }

    public function removeMouvmentStock(MouvmentStock $mouvmentStock): static
    {
        if ($this->mouvmentStocks->removeElement($mouvmentStock)) {
            // set the owning side to null (unless already changed)
            if ($mouvmentStock->getLocalisation() === $this) {
                $mouvmentStock->setLocalisation(null);
            }
        }

        return $this;
    }
}
