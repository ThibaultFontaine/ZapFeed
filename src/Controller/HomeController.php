<?php

namespace App\Controller;

use App\Entity\UserFeed;
use App\Entity\UserItem;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use SimplePie\SimplePie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

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
            $recentItems = $this->entityManager->getRepository(UserItem::class)
                ->findBy(
                    ['user' => $user],
                    ['updatedAt' => 'DESC'],
                    4
                );

            $userFeeds = $this->entityManager->getRepository(UserFeed::class)
                ->findBy(['user' => $user]);

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


    #[Route('/refresh', name: 'app_refresh_feeds', methods: ['POST'])]
    public function refreshFeeds(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        // Récupérer tous les flux de l'utilisateur
        $userFeeds = $this->entityManager->getRepository(UserFeed::class)->findBy(['user' => $user]);

        $newItemsCount = 0;

        foreach ($userFeeds as $userFeed) {
            $feed = $userFeed->getFeed();

            $pie = new SimplePie();
            $pie->enable_cache(false);
            $pie->set_feed_url($feed->getUrl());

            if ($pie->init()) {
                // if (empty($feed->getBaseTitle())) {
                //     $feed->setBaseTitle($pie->get_title());
                //     $this->entityManager->persist($feed);
                // }

                $existingItemUrls = [];
                foreach ($feed->getItems() as $existingItem) {
                    $existingItemUrls[] = $existingItem->getUrl();
                }

                foreach ($pie->get_items() as $key => $_item) {
                    if ($key === 201) {
                        break;
                    }

                    $itemUrl = $_item->get_link();

                    if (!in_array($itemUrl, $existingItemUrls)) {
                        $item = new Item();
                        $item->setFeed($feed);
                        $item->setTitle($_item->get_title());
                        $item->setUrl($itemUrl);
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

                        $newItemsCount++;
                    }
                }
            }

            $userFeed->setUpdatedAt(new \DateTime());
            $this->entityManager->persist($userFeed);
        }

        $this->entityManager->flush();

        // // Add flash message for JavaScript to display
        // $this->addFlash('refresh-result', json_encode([
        //     'count' => $newItemsCount,
        //     'timestamp' => (new \DateTime())->format('H:i:s')
        // ]));

        // Get the referer URL (the page that made the request)
        $referer = $request->headers->get('referer');

        // If we have a referer URL, redirect back to it, otherwise go to homepage
        if ($referer) {
            return $this->redirect($referer);
        } else {
            return $this->redirectToRoute('app_home');
        }
    }
}
