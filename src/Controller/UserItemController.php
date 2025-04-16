<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\UserItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// #[Route('/feed/{feedId}/item')]
final class UserItemController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}


    #[Route('/feed/{feedId}/item/{itemId}', name: 'app_user_item_show', methods: ['GET'])]
    public function show(int $feedId, int $itemId): Response
    {
        $user = $this->getUser();
        $item = $this->entityManager->getRepository(Item::class)->find($itemId);
        $userItem = $this->entityManager->getRepository(UserItem::class)->findOneBy(['item' => $item, 'user' => $user]);

        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        if (!$userItem) {
            throw $this->createNotFoundException('UserItem not found');
        }

        if (!$userItem->hasBeenRead()) {
            // throw $this->createAccessDeniedException('UserItem already read');
            $userItem->setHasBeenRead(true);
            $userItem->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($userItem);
            $this->entityManager->flush();
        }

        // 🔁 Pagination logique
        $userItems = $this->entityManager->getRepository(UserItem::class)->findBy([
            'user' => $user,
        ]);

        // On filtre ceux liés au bon feed
        $userItems = array_filter($userItems, fn($ui) => $ui->getItem()->getFeed()->getId() === $feedId);

        // On les trie par ID (ou autre logique)
        usort($userItems, fn($a, $b) => $a->getItem()->getId() <=> $b->getItem()->getId());

        // Trouver la position de l’item courant
        $currentIndex = array_search($userItem, $userItems);

        $prev = [
            'isDisabled' => $currentIndex <= 0,
            'itemId' => $currentIndex > 0 ? $userItems[$currentIndex - 1]->getItem()->getId() : null,
        ];

        $next = [
            'isDisabled' => $currentIndex >= count($userItems) - 1,
            'itemId' => $currentIndex < count($userItems) - 1 ? $userItems[$currentIndex + 1]->getItem()->getId() : null,
        ];

        return $this->render('user_item/show.html.twig', [
            'user_item' => $userItem,
            'item' => $userItem->getItem(),
            'prev' => $prev,
            'next' => $next,
        ]);
    }

    #[Route('/feed/{feedId}/item/{itemId}/like', name: 'app_user_item_set_like', methods: ['POST'])]
    public function setAsLiked(Request $request, int $feedId, int $itemId): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $item = $this->entityManager->getRepository(Item::class)->find($itemId);

        // if (!$item) {
        //     throw $this->createNotFoundException();
        // }
        if (!$user || !$item) {
            // throw $this->createNotFoundException();
            return $this->json(['error' => 'Not found'], 404);
        }

        $userItem = $this->entityManager->getRepository(UserItem::class)->findOneBy([
            'user' => $user,
            'item' => $item,
        ]);

        if (!$userItem) {
            // $userItem = new UserItem();
            // $userItem->setUser($user);
            // $userItem->setItem($item);
            throw $this->createNotFoundException();
        }

        $userItem->setHasBeenLiked(!$userItem->hasBeenLiked());
        $userItem->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($userItem);
        $this->entityManager->flush();

        return $this->json([
            'liked' => $userItem->hasBeenLiked()
        ]);
    }

    #[Route('/liked', name: 'app_user_item_liked', methods: ['GET'])]
    public function showLiked(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $userItems = $this->entityManager->getRepository(UserItem::class)->findBy([
            'user' => $user,
            'hasBeenLiked' => true,
        ], ['updatedAt' => 'DESC']);

        return $this->render('user_item/like.html.twig', [
            'user_items' => $userItems,
        ]);
    }
}
