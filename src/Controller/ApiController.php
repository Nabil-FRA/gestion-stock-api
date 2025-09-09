<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Fournisseur;
use App\Entity\Categorie;
use App\Entity\Localisation;
use App\Entity\MouvementStock;
use App\Entity\User;
use App\Repository\CategorieRepository;
use App\Repository\FournisseurRepository;
use App\Repository\LocalisationRepository;
use App\Repository\MouvementStockRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    //================================================================
    // PRODUITS
    //================================================================

    #[Route('/produits', name: 'api_produits_list', methods: ['GET'])]
    public function listProduits(ProduitRepository $produitRepository): JsonResponse
    {
        return $this->json($produitRepository->findAll(), Response::HTTP_OK, [], ['groups' => 'product:read']);
    }

    #[Route('/produits/{id}', name: 'api_produits_show', methods: ['GET'])]
    public function showProduit(Produit $produit): JsonResponse
    {
        return $this->json($produit, Response::HTTP_OK, [], ['groups' => 'product:read']);
    }

    #[Route('/produits', name: 'api_produits_new', methods: ['POST'])]
    public function createProduit(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $produit = $serializer->deserialize($request->getContent(), Produit::class, 'json');
        $em->persist($produit);
        $em->flush();
        return $this->json($produit, Response::HTTP_CREATED, [], ['groups' => 'product:read']);
    }

    #[Route('/produits/{id}', name: 'api_produits_update', methods: ['PUT'])]
    public function updateProduit(Produit $produit, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Produit::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $produit]);
        $em->flush();
        return $this->json($produit, Response::HTTP_OK, [], ['groups' => 'product:read']);
    }

    #[Route('/produits/{id}', name: 'api_produits_delete', methods: ['DELETE'])]
    public function deleteProduit(Produit $produit, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($produit);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //================================================================
    // CATEGORIES
    //================================================================

    #[Route('/categories', name: 'api_categories_list', methods: ['GET'])]
    public function listCategories(CategorieRepository $categorieRepository): JsonResponse
    {
        return $this->json($categorieRepository->findAll(), Response::HTTP_OK, [], ['groups' => 'category:read']);
    }

    #[Route('/categories/{id}', name: 'api_categories_show', methods: ['GET'])]
    public function showCategorie(Categorie $categorie): JsonResponse
    {
        return $this->json($categorie, Response::HTTP_OK, [], ['groups' => 'category:read']);
    }

    #[Route('/categories', name: 'api_categories_new', methods: ['POST'])]
    public function createCategorie(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $categorie = $serializer->deserialize($request->getContent(), Categorie::class, 'json');
        $em->persist($categorie);
        $em->flush();
        return $this->json($categorie, Response::HTTP_CREATED, [], ['groups' => 'category:read']);
    }

    #[Route('/categories/{id}', name: 'api_categories_update', methods: ['PUT'])]
    public function updateCategorie(Categorie $categorie, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Categorie::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $categorie]);
        $em->flush();
        return $this->json($categorie, Response::HTTP_OK, [], ['groups' => 'category:read']);
    }

    #[Route('/categories/{id}', name: 'api_categories_delete', methods: ['DELETE'])]
    public function deleteCategorie(Categorie $categorie, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($categorie);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //================================================================
    // FOURNISSEURS
    //================================================================

    #[Route('/fournisseurs', name: 'api_fournisseurs_list', methods: ['GET'])]
    public function listFournisseurs(FournisseurRepository $fournisseurRepository): JsonResponse
    {
        return $this->json($fournisseurRepository->findAll(), Response::HTTP_OK, [], ['groups' => 'supplier:read']);
    }

    #[Route('/fournisseurs/{id}', name: 'api_fournisseurs_show', methods: ['GET'])]
    public function showFournisseur(Fournisseur $fournisseur): JsonResponse
    {
        return $this->json($fournisseur, Response::HTTP_OK, [], ['groups' => 'supplier:read']);
    }

    #[Route('/fournisseurs', name: 'api_fournisseurs_new', methods: ['POST'])]
    public function createFournisseur(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $fournisseur = $serializer->deserialize($request->getContent(), Fournisseur::class, 'json');
        $em->persist($fournisseur);
        $em->flush();
        return $this->json($fournisseur, Response::HTTP_CREATED, [], ['groups' => 'supplier:read']);
    }

    #[Route('/fournisseurs/{id}', name: 'api_fournisseurs_update', methods: ['PUT'])]
    public function updateFournisseur(Fournisseur $fournisseur, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Fournisseur::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $fournisseur]);
        $em->flush();
        return $this->json($fournisseur, Response::HTTP_OK, [], ['groups' => 'supplier:read']);
    }

    #[Route('/fournisseurs/{id}', name: 'api_fournisseurs_delete', methods: ['DELETE'])]
    public function deleteFournisseur(Fournisseur $fournisseur, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($fournisseur);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //================================================================
    // LOCALISATIONS
    //================================================================

    #[Route('/localisations', name: 'api_localisations_list', methods: ['GET'])]
    public function listLocalisations(LocalisationRepository $localisationRepository): JsonResponse
    {
        return $this->json($localisationRepository->findAll(), Response::HTTP_OK, [], ['groups' => 'location:read']);
    }

    #[Route('/localisations/{id}', name: 'api_localisations_show', methods: ['GET'])]
    public function showLocalisation(Localisation $localisation): JsonResponse
    {
        return $this->json($localisation, Response::HTTP_OK, [], ['groups' => 'location:read']);
    }

    #[Route('/localisations', name: 'api_localisations_new', methods: ['POST'])]
    public function createLocalisation(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $localisation = $serializer->deserialize($request->getContent(), Localisation::class, 'json');
        $em->persist($localisation);
        $em->flush();
        return $this->json($localisation, Response::HTTP_CREATED, [], ['groups' => 'location:read']);
    }

    #[Route('/localisations/{id}', name: 'api_localisations_update', methods: ['PUT'])]
    public function updateLocalisation(Localisation $localisation, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Localisation::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $localisation]);
        $em->flush();
        return $this->json($localisation, Response::HTTP_OK, [], ['groups' => 'location:read']);
    }

    #[Route('/localisations/{id}', name: 'api_localisations_delete', methods: ['DELETE'])]
    public function deleteLocalisation(Localisation $localisation, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($localisation);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //================================================================
    // MOUVEMENTS DE STOCK
    //================================================================

    #[Route('/mouvements', name: 'api_mouvements_list', methods: ['GET'])]
    public function listMouvements(MouvementStockRepository $mouvementStockRepository): JsonResponse
    {
        return $this->json($mouvementStockRepository->findAll(), Response::HTTP_OK, [], ['groups' => 'movement:read']);
    }

    #[Route('/mouvements', name: 'api_mouvements_new', methods: ['POST'])]
    public function createMouvement(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $mouvement = $serializer->deserialize($request->getContent(), MouvementStock::class, 'json');

        $user = $this->getUser();
        if ($user instanceof User) {
            $mouvement->setUtilisateur($user);
        }

        $em->persist($mouvement);
        $em->flush();

        return $this->json($mouvement, Response::HTTP_CREATED, [], ['groups' => 'movement:read']);
    }

    //================================================================
    // UTILISATEURS (INSCRIPTION)
    //================================================================

    #[Route('/users', name: 'api_users_new', methods: ['POST'])]
    public function createUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        return $this->json($user, Response::HTTP_CREATED, [], ['groups' => 'user:read']);
    }
}

