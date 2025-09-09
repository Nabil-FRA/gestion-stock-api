<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:read', 'movement:read', 'category:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read', 'movement:read', 'category:read'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['product:read'])]
    private ?string $reference = null;

    #[ORM\Column]
    #[Groups(['product:read'])]
    private ?int $seuilDAlerte = null; // CORRECTION : Convention de nommage

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['product:read'])]
    private ?Categorie $categorie = null;

    /**
     * @var Collection<int, MouvementStock>
     */
    #[ORM\OneToMany(targetEntity: MouvementStock::class, mappedBy: 'produit')]
    private Collection $mouvementStocks; // CORRECTION : Typo

    /**
     * @var Collection<int, Fournisseur>
     */
    #[ORM\ManyToMany(targetEntity: Fournisseur::class, inversedBy: 'produits')]
    #[Groups(['product:read'])]
    private Collection $fournisseurs;

    public function __construct()
    {
        $this->mouvementStocks = new ArrayCollection(); // CORRECTION : Typo
        $this->fournisseurs = new ArrayCollection();
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

    public function getSeuilDAlerte(): ?int
    {
        return $this->seuilDAlerte;
    }

    public function setSeuilDAlerte(int $seuilDAlerte): static
    {
        $this->seuilDAlerte = $seuilDAlerte;
        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;
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
            $mouvementStock->setProduit($this);
        }
        return $this;
    }

    public function removeMouvementStock(MouvementStock $mouvementStock): static // CORRECTION : Typo
    {
        if ($this->mouvementStocks->removeElement($mouvementStock)) { // CORRECTION : Typo
            if ($mouvementStock->getProduit() === $this) {
                $mouvementStock->setProduit(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Fournisseur>
     */
    public function getFournisseurs(): Collection
    {
        return $this->fournisseurs;
    }

    public function addFournisseur(Fournisseur $fournisseur): static
    {
        if (!$this->fournisseurs->contains($fournisseur)) {
            $this->fournisseurs->add($fournisseur);
        }
        return $this;
    }

    public function removeFournisseur(Fournisseur $fournisseur): static
    {
        $this->fournisseurs->removeElement($fournisseur);
        return $this;
    }
}
