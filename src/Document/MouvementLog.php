<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class MouvementLog
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: 'date')]
    private \DateTime $date;

    #[ODM\Field(type: 'string')]
    private string $type;

    #[ODM\Field(type: 'int')]
    private int $quantite;

    #[ODM\Field(type: 'string')]
    private string $produitNom;

    #[ODM\Field(type: 'string')]
    private string $localisationNom;

    #[ODM\Field(type: 'string')]
    private string $utilisateurEmail;

    // --- Getters and Setters ---

    public function getId(): ?string { return $this->id; }
    public function getDate(): \DateTime { return $this->date; }
    public function setDate(\DateTime $date): self { $this->date = $date; return $this; }
    public function getType(): string { return $this->type; }
    public function setType(string $type): self { $this->type = $type; return $this; }
    public function getQuantite(): int { return $this->quantite; }
    public function setQuantite(int $quantite): self { $this->quantite = $quantite; return $this; }
    public function getProduitNom(): string { return $this->produitNom; }
    public function setProduitNom(string $produitNom): self { $this->produitNom = $produitNom; return $this; }
    public function getLocalisationNom(): string { return $this->localisationNom; }
    public function setLocalisationNom(string $localisationNom): self { $this->localisationNom = $localisationNom; return $this; }
    public function getUtilisateurEmail(): string { return $this->utilisateurEmail; }
    public function setUtilisateurEmail(string $utilisateurEmail): self { $this->utilisateurEmail = $utilisateurEmail; return $this; }
}
