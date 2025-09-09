<?php

namespace App\Entity;

use App\Repository\MouvementStockRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MouvementStockRepository::class)]
class MouvementStock // CORRECTION : Nom de la classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movement:read'])]
    private ?string $type = null;

    #[ORM\Column]
    #[Groups(['movement:read'])]
    private ?int $quantite = null;

    #[ORM\Column]
    #[Groups(['movement:read'])]
    private ?\DateTime $dateMouvement = null; // CORRECTION : Convention de nommage (camelCase)

    #[ORM\ManyToOne(inversedBy: 'mouvementStocks')]
    #[Groups(['movement:read'])]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementStocks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['movement:read'])]
    private ?Localisation $localisation = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementStocks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['movement:read'])]
    private ?User $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getDateMouvement(): ?\DateTime
    {
        return $this->dateMouvement;
    }

    public function setDateMouvement(\DateTime $dateMouvement): static
    {
        $this->dateMouvement = $dateMouvement;
        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;
        return $this;
    }

    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(?Localisation $localisation): static
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
}
