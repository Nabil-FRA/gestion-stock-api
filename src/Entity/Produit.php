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
    private ?int $seuil_d_alerte = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['product:read'])]
    private ?Categorie $categorie = null; // <-- CORRIGÉ : C majuscule

    /**
     * @var Collection<int, MouvmentStock>
     */
    #[ORM\OneToMany(targetEntity: MouvmentStock::class, mappedBy: 'produit')]

    private Collection $mouvmentStocks;

    /**
     * @var Collection<int, Fournisseur>
     */
    #[ORM\ManyToMany(targetEntity: Fournisseur::class, inversedBy: 'produits')]
    #[Groups(['product:read'])]
    private Collection $fournisseurs;

    public function __construct()
    {
        $this->mouvmentStocks = new ArrayCollection();
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
        return $this->seuil_d_alerte;
    }

    public function setSeuilDAlerte(int $seuil_d_alerte): static
    {
        $this->seuil_d_alerte = $seuil_d_alerte;

        return $this;
    }

    public function getCategorie(): ?Categorie // <-- CORRIGÉ : C majuscule
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static // <-- CORRIGÉ : C majuscule
    {
        $this->categorie = $categorie;

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
            $mouvmentStock->setProduit($this);
        }

        return $this;
    }

    public function removeMouvmentStock(MouvmentStock $mouvmentStock): static
    {
        if ($this->mouvmentStocks->removeElement($mouvmentStock)) {
            // set the owning side to null (unless already changed)
            if ($mouvmentStock->getProduit() === $this) {
                $mouvmentStock->setProduit(null);
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
