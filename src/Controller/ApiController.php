<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FournisseurRepository;
use App\Repository\LocalisationRepository;
use App\Repository\MouvmentStockRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Fournisseur;
use App\Entity\Categorie;
use App\Entity\Localisation;
use App\Entity\MouvmentStock;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Response;
use App\Document\MouvementLog;
use App\Service\StockService;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/produits', name: 'api_produits_list', methods: ['GET'])]
    public function listProduits(ProduitRepository $produitRepository): JsonResponse
    {
        $produits = $produitRepository->findAll();
        return $this->json($produits, 200, [], ['groups' => 'product:read']);
    }

    #[Route('/categories', name: 'api_categories_list', methods: ['GET'])]
    public function listCategories(CategorieRepository $categorieRepository): JsonResponse
    {
        $categories = $categorieRepository->findAll();
        return $this->json($categories, 200, [], ['groups' => 'category:read']);
    }

    #[Route('/fournisseurs', name: 'api_fournisseurs_list', methods: ['GET'])]
    public function listFournisseurs(FournisseurRepository $fournisseurRepository): JsonResponse
    {
        $fournisseurs = $fournisseurRepository->findAll();
        return $this->json($fournisseurs, 200, [], ['groups' => 'supplier:read']);
    }

    #[Route('/localisations', name: 'api_localisations_list', methods: ['GET'])]
    public function listLocalisations(LocalisationRepository $localisationRepository): JsonResponse
    {
        $localisations = $localisationRepository->findAll();
        return $this->json($localisations, 200, [], ['groups' => 'location:read']);
    }

    #[Route('/mouvements', name: 'api_mouvements_list', methods: ['GET'])]
    public function listMouvements(MouvmentStockRepository $mouvmentStockRepository): JsonResponse
    {
        $mouvements = $mouvmentStockRepository->findAll();
        return $this->json($mouvements, 200, [], ['groups' => 'movement:read']);
    }

    #[Route('/produits', name: 'api_produits_new', methods: ['POST'])]
    public function createProduit(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        CategorieRepository $categorieRepository // <-- 1. AJOUTEZ CECI
    ): JsonResponse {
        $jsonContent = $request->getContent();
        $produit = $serializer->deserialize($jsonContent, Produit::class, 'json');

        // --- 2. AJOUTEZ CE BLOC DE VÉRIFICATION ---
        $data = json_decode($jsonContent, true);
        if (isset($data['categorie']['id'])) {
            $idCategorie = $data['categorie']['id'];
            $categorie = $categorieRepository->find($idCategorie);

            if (!$categorie) {
                return $this->json(['message' => 'Catégorie non trouvée'], Response::HTTP_BAD_REQUEST);
            }
            // On assigne la vraie catégorie gérée par Doctrine
            $produit->setCategorie($categorie);
        } else {
            return $this->json(['message' => 'ID de catégorie manquant'], Response::HTTP_BAD_REQUEST);
        }
        // --- FIN DU BLOC AJOUTÉ ---

        $em->persist($produit);
        $em->flush();

        return $this->json($produit, 201, [], ['groups' => 'product:read']);
    }
    #[Route('/produits/{id}', name: 'api_produits_update', methods: ['PUT'])]
    public function updateProduit(
        Produit $produit,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        CategorieRepository $categorieRepository // <-- 1. AJOUTEZ CECI
    ): JsonResponse {
        $jsonContent = $request->getContent();
        $serializer->deserialize($jsonContent, Produit::class, 'json', ['object_to_populate' => $produit]);

        // --- 2. AJOUTEZ CE BLOC DE VÉRIFICATION ---
        // Vérifie si une catégorie a été fournie dans le JSON
        $data = json_decode($jsonContent, true);
        if (isset($data['categorie']['id'])) {
            $idCategorie = $data['categorie']['id'];
            $categorie = $categorieRepository->find($idCategorie);

            if (!$categorie) {
                return $this->json(['message' => 'Catégorie non trouvée'], Response::HTTP_BAD_REQUEST);
            }
            // On assigne la vraie catégorie gérée par Doctrine
            $produit->setCategorie($categorie);
        }
        // --- FIN DU BLOC AJOUTÉ ---

        $em->flush();

        return $this->json($produit, 200, [], ['groups' => 'product:read']);
    }

    #[Route('/produits/{id}', name: 'api_produits_show', methods: ['GET'])]
    public function showProduit(Produit $produit): JsonResponse
    {
        // Grâce au ParamConverter de Symfony, l'objet $produit est
        // automatiquement récupéré depuis la base de données via l'ID dans l'URL.
        return $this->json($produit, 200, [], ['groups' => 'product:read']);
    }

    #[Route('/produits/{id}', name: 'api_produits_delete', methods: ['DELETE'])]
    public function deleteProduit(Produit $produit, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($produit);
        $em->flush();

        // Le code 204 "No Content" est le standard pour une suppression réussie
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    #[Route('/fournisseurs/{id}', name: 'api_fournisseurs_show', methods: ['GET'])]
    public function showFournisseur(Fournisseur $fournisseur): JsonResponse
    {
        return $this->json($fournisseur, 200, [], ['groups' => 'supplier:read']);
    }

    #[Route('/fournisseurs', name: 'api_fournisseurs_new', methods: ['POST'])]
    public function createFournisseur(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonContent = $request->getContent();
        $fournisseur = $serializer->deserialize($jsonContent, Fournisseur::class, 'json');
        $em->persist($fournisseur);
        $em->flush();
        return $this->json($fournisseur, 201, [], ['groups' => 'supplier:read']);
    }

    #[Route('/fournisseurs/{id}', name: 'api_fournisseurs_update', methods: ['PUT'])]
    public function updateFournisseur(Fournisseur $fournisseur, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonContent = $request->getContent();
        $serializer->deserialize($jsonContent, Fournisseur::class, 'json', ['object_to_populate' => $fournisseur]);
        $em->flush();
        return $this->json($fournisseur, 200, [], ['groups' => 'supplier:read']);
    }

    #[Route('/fournisseurs/{id}', name: 'api_fournisseurs_delete', methods: ['DELETE'])]
    public function deleteFournisseur(Fournisseur $fournisseur, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($fournisseur);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/categories/{id}', name: 'api_categories_show', methods: ['GET'])]
    public function showCategorie(Categorie $categorie): JsonResponse
    {
        return $this->json($categorie, 200, [], ['groups' => 'category:read']);
    }

    #[Route('/categories', name: 'api_categories_new', methods: ['POST'])]
    public function createCategorie(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonContent = $request->getContent();
        $categorie = $serializer->deserialize($jsonContent, Categorie::class, 'json');
        $em->persist($categorie);
        $em->flush();
        return $this->json($categorie, 201, [], ['groups' => 'category:read']);
    }

    #[Route('/categories/{id}', name: 'api_categories_update', methods: ['PUT'])]
    public function updateCategorie(Categorie $categorie, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonContent = $request->getContent();
        $serializer->deserialize($jsonContent, Categorie::class, 'json', ['object_to_populate' => $categorie]);
        $em->flush();
        return $this->json($categorie, 200, [], ['groups' => 'category:read']);
    }

    #[Route('/categories/{id}', name: 'api_categories_delete', methods: ['DELETE'])]
    public function deleteCategorie(Categorie $categorie, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($categorie);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/localisations/{id}', name: 'api_localisations_show', methods: ['GET'])]
    public function showLocalisation(Localisation $localisation): JsonResponse
    {
        return $this->json($localisation, 200, [], ['groups' => 'location:read']);
    }

    #[Route('/localisations', name: 'api_localisations_new', methods: ['POST'])]
    public function createLocalisation(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonContent = $request->getContent();
        $localisation = $serializer->deserialize($jsonContent, Localisation::class, 'json');
        $em->persist($localisation);
        $em->flush();
        return $this->json($localisation, 201, [], ['groups' => 'location:read']);
    }

    #[Route('/localisations/{id}', name: 'api_localisations_update', methods: ['PUT'])]
    public function updateLocalisation(Localisation $localisation, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonContent = $request->getContent();
        $serializer->deserialize($jsonContent, Localisation::class, 'json', ['object_to_populate' => $localisation]);
        $em->flush();
        return $this->json($localisation, 200, [], ['groups' => 'location:read']);
    }

    #[Route('/localisations/{id}', name: 'api_localisations_delete', methods: ['DELETE'])]
    public function deleteLocalisation(Localisation $localisation, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($localisation);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/mouvements', name: 'api_mouvements_new', methods: ['POST'])]
    public function createMouvement(
        Request $request,
        ProduitRepository $produitRepository,
        LocalisationRepository $localisationRepository,
        StockService $stockService // <-- Injectez le nouveau service
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $produit = $produitRepository->find($data['produit']);
        $localisation = $localisationRepository->find($data['localisation']);

        if (!$produit || !$localisation) {
            return $this->json(['message' => 'Produit ou Localisation introuvable'], Response::HTTP_BAD_REQUEST);
        }

        // Utilisez le service pour créer le mouvement
        $mouvement = $stockService->createMouvement(
            $produit,
            $localisation,
            $data['quantite'],
            $data['type'],
            $this->getUser()
        );

        return $this->json($mouvement, 201, [], ['groups' => 'movement:read']);
    }

    #[Route('/audit-logs', name: 'api_audit_logs_list', methods: ['GET'])]
    public function listAuditLogs(DocumentManager $dm): JsonResponse
    {
        $logs = $dm->getRepository(MouvementLog::class)->findAll();
        return $this->json($logs);
    }

     #[Route('/users', name: 'api_users_new', methods: ['POST'])]
    public function createUser(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        // On récupère le contenu de la requête (le JSON)
        $jsonContent = $request->getContent();

        // On "désérialise" le JSON pour créer un objet User
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // On hache le mot de passe avant de le sauvegarder
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPassword() // On récupère le mot de passe en clair du JSON
        );
        $user->setPassword($hashedPassword);

        // On définit un rôle par défaut si besoin
        $user->setRoles(['ROLE_USER']);

        // On sauvegarde le nouvel utilisateur en base de données
        $em->persist($user);
        $em->flush();

        // On retourne l'utilisateur créé (sans le mot de passe) avec un statut 201
        return $this->json($user, 201, [], ['groups' => 'user:read']);
    }
}
