<?php

namespace App\Controller;

use App\Entity\UserFeed;
use App\Entity\UserItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class HomeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route(name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        $recentItems = [];
        $userFeeds = [];
        $user = $this->getUser();

        if ($user) {
            // Récupération des 4 derniers articles de l'utilisateur
            $recentItems = $this->entityManager->getRepository(UserItem::class)
                ->findBy(
                    ['user' => $user],
                    ['updatedAt' => 'DESC'],
                    4
                );

            // Récupération de tous les UserFeeds de l'utilisateur pour associer les titres personnalisés
            $userFeeds = $this->entityManager->getRepository(UserFeed::class)
                ->findBy(['user' => $user]);

            // Création d'un tableau associatif pour faciliter l'accès aux UserFeed par feed_id
            $userFeedsByFeedId = [];
            foreach ($userFeeds as $userFeed) {
                $userFeedsByFeedId[$userFeed->getFeed()->getId()] = $userFeed;
            }
        }

        return $this->render('index.html.twig', [
            'recentItems' => $recentItems,
            'userFeedsByFeedId' => $userFeedsByFeedId ?? []
        ]);
    }

    #[Route('/about', name: 'app_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }
}
