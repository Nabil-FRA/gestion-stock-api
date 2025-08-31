<?php
namespace App\Service;

use App\Document\MouvementLog;
use App\Entity\MouvmentStock;
use App\Entity\Produit;
use App\Entity\Localisation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

class StockService
{
    private EntityManagerInterface $em;
    private DocumentManager $dm;

    public function __construct(EntityManagerInterface $em, DocumentManager $dm)
    {
        $this->em = $em;
        $this->dm = $dm;
    }

    public function createMouvement(Produit $produit, Localisation $localisation, int $quantite, string $type, User $user): MouvmentStock
    {
        // 1. Écriture dans SQL
        $mouvement = new MouvmentStock();
        $mouvement->setProduit($produit);
        $mouvement->setLocalisation($localisation);
        $mouvement->setQuantite($quantite);
        $mouvement->setType($type);
        $mouvement->setUtilisateur($user);
        $mouvement->setDateMouvement(new \DateTime());
        $this->em->persist($mouvement);

        // 2. Écriture dans MongoDB
        $log = new MouvementLog();
        $log->setDate(new \DateTime());
        $log->setType($type);
        $log->setQuantite($quantite);
        $log->setProduitNom($produit->getNom());
        $log->setLocalisationNom($localisation->getNom());
        $log->setUtilisateurEmail($user->getEmail());
        $this->dm->persist($log);

        // 3. Sauvegarde des changements
        $this->em->flush();
        $this->dm->flush();

        return $mouvement;
    }
}
