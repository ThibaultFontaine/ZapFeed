<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\UserFeed;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/catalog')]
class CatalogController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route(name: 'app_catalog', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('catalog/index.html.twig', [
            'feeds' => $this->entityManager->getRepository(Feed::class)->findAll(),
            'user_feeds' => $this->entityManager->getRepository(UserFeed::class)->findBy(['user' => $this->getUser()]),
        ]);
    }
}
