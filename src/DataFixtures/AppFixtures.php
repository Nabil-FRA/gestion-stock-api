<?php
namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Fournisseur;
use App\Entity\Localisation;
use App\Entity\MouvmentStock; // Correction orthographique selon votre entité
use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- Utilisateur ---
        $adminUser = new User();
        $adminUser->setEmail('admin@test.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, 'password'));
        $manager->persist($adminUser);

        // --- Localisation ---
        $location1 = new Localisation();
        $location1->setNom('Entrepôt Principal');
        $location1->setAdresse('123 Rue du Dépôt, 75001 Paris');
        $manager->persist($location1);

        // --- Fournisseur ---
        $supplier1 = new Fournisseur();
        $supplier1->setNom('Fournisseur Tech');
        $supplier1->setContact('contact@fournisseur-tech.com');
        $manager->persist($supplier1);

        $supplier2 = new Fournisseur();
        $supplier2->setNom('Grossiste Bureau');
        $supplier2->setContact('contact@grossiste-bureau.com');
        $manager->persist($supplier2);

        // --- Categorie ---
        $catInformatique = new Categorie();
        $catInformatique->setNom('Informatique');
        $manager->persist($catInformatique);

        // --- Produit ---
        $product1 = new Produit();
        $product1->setNom('Souris Laser Pro');
        $product1->setReference('SLP-2025');
        $product1->setSeuilDAlerte(10); // Utilise la méthode de votre entité
        $product1->setCategorie($catInformatique);
        $product1->addFournisseur($supplier1);
        $manager->persist($product1);

        $product2 = new Produit();
        $product2->setNom('Clavier Mécanique RGB');
        $product2->setReference('CMR-2025');
        $product2->setSeuilDAlerte(5);
        $product2->setCategorie($catInformatique);
        $product2->addFournisseur($supplier1);
        $product2->addFournisseur($supplier2);
        $manager->persist($product2);

        // --- Mouvement de Stock (Exemple d'entrée de stock initial) ---
        $stockInitial = new MouvmentStock();
        $stockInitial->setType('entrée');
        $stockInitial->setQuantite(50);
        $stockInitial->setDateMouvement(new \DateTime()); // Utilise la méthode de votre entité
        $stockInitial->setProduit($product1);
        $stockInitial->setLocalisation($location1);
        $stockInitial->setUtilisateur($adminUser);
        $manager->persist($stockInitial);

        // --- Enregistrement final ---
        $manager->flush();
    }
}
