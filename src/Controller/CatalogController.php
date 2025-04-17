<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\UserFeed;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\Expr;

#[Route('/catalog')]
class CatalogController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route(name: 'app_catalog', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        $search = $request->query->get('search', '');
        $feedRepository = $this->entityManager->getRepository(Feed::class);

        // Si un terme de recherche est fourni
        if ($search) {
            // Normalisation du terme de recherche (suppression des accents et passage en minuscules)
            $normalizedSearch = $this->normalizeString($search);

            // Utilisation de la fonction LOWER pour une recherche insensible à la casse
            $queryBuilder = $feedRepository->createQueryBuilder('f');

            // Selon le SGBD utilisé, la stratégie peut être différente
            // Pour MySQL/MariaDB, on peut utiliser la fonction LOWER
            $queryBuilder
                ->where('LOWER(f.baseTitle) LIKE LOWER(:search)')
                ->setParameter('search', '%' . $normalizedSearch . '%');

            $feeds = $queryBuilder->getQuery()->getResult();
        } else {
            $feeds = $feedRepository->findAll();
        }

        return $this->render('catalog/index.html.twig', [
            'feeds' => $feeds,
            'user_feeds' => $this->entityManager->getRepository(UserFeed::class)->findBy(['user' => $this->getUser()]),
            'search' => $search,
        ]);
    }

    /**
     * Normalise une chaîne de caractères pour la recherche
     * - Supprime les accents
     * - Convertit en minuscules
     */
    private function normalizeString(string $string): string
    {
        // Conversion en minuscules
        $string = mb_strtolower($string, 'UTF-8');

        // Tableau de correspondance pour les caractères accentués
        $search = ['é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'á', 'ã', 'å', 'ç', 'î', 'ï', 'í', 'ì', 'ñ', 'ô', 'ö', 'ò', 'ó', 'õ', 'û', 'ü', 'ù', 'ú', 'ÿ'];
        $replace = ['e', 'e', 'e', 'e', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y'];

        // Remplacement des caractères accentués
        return str_replace($search, $replace, $string);
    }

    #[Route('/{feedId}/subscribe', name: 'app_catalog_subscribe', methods: ['POST'])]
    public function subscribe(Request $request, int $feedId): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not logged in'], Response::HTTP_UNAUTHORIZED);
        }

        $feed = $this->entityManager->getRepository(Feed::class)->find($feedId);
        if (!$feed) {
            return new JsonResponse(['success' => false, 'message' => 'Feed not found'], Response::HTTP_NOT_FOUND);
        }

        // Check if user is already subscribed to this feed
        $existingSubscription = $this->entityManager->getRepository(UserFeed::class)->findOneBy([
            'user' => $user,
            'feed' => $feed
        ]);

        if ($existingSubscription) {
            return new JsonResponse([
                'success' => false,
                'message' => 'You are already subscribed to this feed'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Create new subscription
        $userFeed = new UserFeed();
        $userFeed->setUser($user);
        $userFeed->setFeed($feed);
        $userFeed->setTitle($feed->getBaseTitle());
        $userFeed->setCreatedAt(new \DateTimeImmutable());
        $userFeed->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($userFeed);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Subscribed successfully'
        ], Response::HTTP_OK);
    }
}
