<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\UserFeed;
use App\Entity\Item;
use App\Entity\UserItem;
use App\Form\FeedType;
use Doctrine\ORM\EntityManagerInterface;
use SimplePie\SimplePie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/feed')]
final class UserFeedController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}


    #[Route(name: 'app_user_feed_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        return $this->render('user_feed/index.html.twig', [
            'user_feeds' => $this->entityManager->getRepository(UserFeed::class)->findBy(['user' => $user]),
        ]);
    }

    #[Route('/new', name: 'app_user_feed_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $form = $this->createForm(FeedType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get feed data from the form
            $url = $form->get('url')->getData();
            $name = $form->get('name')->getData();

            // Check if a feed with this URL already exists
            $feed = $this->entityManager->getRepository(Feed::class)->findOneBy(['url' => $url]);

            // If the feed does not exist, create it
            if (!$feed) {
                $feed = new Feed();
                $feed->setUrl($url);
                $this->entityManager->persist($feed);
                $this->entityManager->flush(); // Flush to save the new feed
            }

            $user = $this->getUser();
            if (!$user) {
                throw $this->createAccessDeniedException('User not logged in');
            }

            // Create a new UserFeed entity
            $userFeed = new UserFeed();
            $userFeed->setUser($user);
            $userFeed->setFeed($feed);
            $userFeed->setTitle($name);
            $userFeed->setCreatedAt(new \DateTimeImmutable());
            $userFeed->setUpdatedAt(new \DateTime());

            $pie = new SimplePie;
            $pie->enable_cache(false);
            $pie->set_feed_url($feed->getUrl());

            if ($pie->init()) {
                $feed->setBaseTitle($pie->get_title());

                foreach ($pie->get_items() as $key => $_item) {
                    if ($key === 201) {
                        break;
                    }
                    $item = new Item();
                    $item->setFeed($feed);
                    $item->setTitle($_item->get_title());
                    $item->setUrl($_item->get_link());
                    $item->setDescription($_item->get_description());

                    $this->entityManager->persist($item);
                    $this->entityManager->flush();

                    $userItem = new UserItem();
                    $userItem->setUser($user);
                    $userItem->setItem($item);
                    $userItem->setHasBeenRead(false);
                    $userItem->setHasBeenLiked(false);
                    $userItem->setCreatedAt(new \DateTimeImmutable());
                    $userItem->setUpdatedAt(new \DateTime());
                    $this->entityManager->persist($userItem);
                    $this->entityManager->flush();
                }
            }

            $this->entityManager->persist($userFeed);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_feed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_feed/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_feed_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        // Vérifier que l'utilisateur actuel est bien le propriétaire de ce UserFeed
        $feed = $this->entityManager->getRepository(Feed::class)->find($id);
        if (!$feed) {
            throw $this->createNotFoundException('Feed not found');
        }
        
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }
        
        $userFeed = $this->entityManager->getRepository(UserFeed::class)->findOneBy(['feed' => $feed, 'user' => $user]);
        if (!$userFeed) {
            throw $this->createNotFoundException('UserFeed not found');
        }
        
        $this->checkUserFeedOwnership($userFeed);

        $userItems = $this->entityManager->getRepository(UserItem::class)->findBy([
            'user' => $user,
        ]);

        // Filtrer sur les items de CE feed + non lus
        $unreadItems = array_filter(
            $userItems,
            fn($ui) =>
            $ui->getItem()->getFeed()->getId() === $userFeed->getFeed()->getId()
                && !$ui->hasBeenRead()
        );

        // Trier par ID (ou autre critère comme pubDate ou createdAt)
        usort($unreadItems, fn($a, $b) => $a->getItem()->getId() <=> $b->getItem()->getId());

        $firstUnread = $unreadItems[0] ?? null;


        return $this->render('user_feed/show.html.twig', [
            'user_feed' => $userFeed,
            'first_unread' => $firstUnread,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_feed_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        // Vérifier que l'utilisateur actuel est bien le propriétaire de ce UserFeed
        $feed = $this->entityManager->getRepository(Feed::class)->find($id);
        if (!$feed) {
            throw $this->createNotFoundException('Feed not found');
        }
        
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }
        
        $userFeed = $this->entityManager->getRepository(UserFeed::class)->findOneBy(['feed' => $feed, 'user' => $user]);
        if (!$userFeed) {
            throw $this->createNotFoundException('UserFeed not found');
        }
        
        $this->checkUserFeedOwnership($userFeed);

        // Créer le formulaire avec les données existantes
        $formData = [
            'name' => $userFeed->getTitle(),
        ];

        $form = $this->createForm(FeedType::class, $formData);
        $form->remove('url'); // Supprimer le champ URL du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newTitle = $form->get('name')->getData();

            // Mettre à jour le titre et la date de mise à jour
            $userFeed->setTitle($newTitle);
            $userFeed->setUpdatedAt(new \DateTime());

            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_feed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_feed/edit.html.twig', [
            'user_feed' => $userFeed,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_feed_delete', methods: ['POST'])]
    public function delete(Request $request, int $id): Response
    {
        // Vérifier que l'utilisateur actuel est bien le propriétaire de ce UserFeed
        $feed = $this->entityManager->getRepository(Feed::class)->find($id);
        if (!$feed) {
            throw $this->createNotFoundException('Feed not found');
        }
        
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }
        
        $userFeed = $this->entityManager->getRepository(UserFeed::class)->findOneBy(['feed' => $feed, 'user' => $user]);
        if (!$userFeed) {
            throw $this->createNotFoundException('UserFeed not found');
        }
        
        $this->checkUserFeedOwnership($userFeed);

        // if ($this->isCsrfTokenValid('delete' . $userFeed->getUser()->getId() . $userFeed->getFeed()->getId(), $request->getPayload()->getString('_token'))) {
        if ($this->isCsrfTokenValid('delete' . $userFeed->getFeed()->getId(), $request->get('_token'))) {
            $this->entityManager->remove($userFeed);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_user_feed_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Vérifie que l'utilisateur connecté est bien le propriétaire du UserFeed
     */
    private function checkUserFeedOwnership(UserFeed $userFeed): void
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        if ($userFeed->getUser() !== $user) {
            throw $this->createAccessDeniedException('You do not have permission to access this feed');
        }
    }
}
