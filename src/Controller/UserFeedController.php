<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\UserFeed;
use App\Entity\Item;
use App\Entity\UserItem;
use App\Form\FeedType;
use App\Repository\FeedRepository;
use App\Repository\UserFeedRepository;
use Doctrine\ORM\EntityManagerInterface;
use SimplePie\SimplePie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/feed')]
final class UserFeedController extends AbstractController
{
    #[Route(name: 'app_user_feed_index', methods: ['GET'])]
    public function index(UserFeedRepository $userFeedRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        return $this->render('user_feed/index.html.twig', [
            'user_feeds' => $userFeedRepository->findBy(['user' => $user]),
        ]);
    }

    #[Route('/new', name: 'app_user_feed_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FeedRepository $feedRepository): Response
    {
        $form = $this->createForm(FeedType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get feed data from the form
            $url = $form->get('url')->getData();
            $name = $form->get('name')->getData();

            // Check if a feed with this URL already exists
            $feed = $feedRepository->findOneBy(['url' => $url]);

            // If the feed does not exist, create it
            if (!$feed) {
                $feed = new Feed();
                $feed->setUrl($url);
                $entityManager->persist($feed);
                $entityManager->flush(); // Flush to save the new feed
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
                dump($pie->get_title());
                dump($pie->get_description());

                foreach ($pie->get_items() as $_item) {
                    $item = new Item();
                    $item->setFeed($feed);
                    $item->setTitle($_item->get_title());
                    $item->setUrl($_item->get_link());
                    $item->setDescription($_item->get_description());
                    /* if ($_item->get_thumbnail()) {
                        $item->setMediaLink($_item->get_thumbnail()->get_link());
                    } elseif ($_item->get_enclosure()) {
                        $item->setMediaLink($_item->get_enclosure()->get_link());
                    } else {
                        $item->setMediaLink(null);
                    } */


                    // Persist the feed item
                    $entityManager->persist($item);
                    $entityManager->flush();

                    $userItem = new UserItem();
                    $userItem->setUser($user);
                    $userItem->setItem($item);
                    $userItem->setHasBeenRead(false);
                    $userItem->setHasBeenLiked(false);
                    $userItem->setCreatedAt(new \DateTimeImmutable());
                    $userItem->setUpdatedAt(new \DateTime());
                    $entityManager->persist($userItem);
                    $entityManager->flush();
                }
            }

            $entityManager->persist($userFeed);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_feed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_feed/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_feed_show', methods: ['GET'])]
    public function show(Feed $feed, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur actuel est bien le propriétaire de ce UserFeed
        $userFeed = $entityManager->getRepository(UserFeed::class)->findOneBy(['feed' => $feed]);
        $this->checkUserFeedOwnership($userFeed);

        return $this->render('user_feed/show.html.twig', [
            'user_feed' => $userFeed,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_feed_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Feed $feed, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur actuel est bien le propriétaire de ce UserFeed
        $userFeed = $entityManager->getRepository(UserFeed::class)->findOneBy(['feed' => $feed]);
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

            $entityManager->flush();

            return $this->redirectToRoute('app_user_feed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_feed/edit.html.twig', [
            'user_feed' => $userFeed,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_feed_delete', methods: ['POST'])]
    public function delete(Request $request, Feed $feed, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur actuel est bien le propriétaire de ce UserFeed
        $userFeed = $entityManager->getRepository(UserFeed::class)->findOneBy(['feed' => $feed]);
        if (!$userFeed) {
            throw $this->createNotFoundException('UserFeed not found');
        }
        $this->checkUserFeedOwnership($userFeed);

        // if ($this->isCsrfTokenValid('delete' . $userFeed->getUser()->getId() . $userFeed->getFeed()->getId(), $request->getPayload()->getString('_token'))) {
        if ($this->isCsrfTokenValid('delete' . $userFeed->getFeed()->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($userFeed);
            $entityManager->flush();
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
