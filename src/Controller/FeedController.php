<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\UserFeed;
use App\Form\FeedType;
use App\Repository\FeedRepository;
use App\Repository\UserFeedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/feed')]
final class FeedController extends AbstractController
{
    #[Route(name: 'app_feed_index', methods: ['GET'])]
    public function index(UserFeedRepository $userFeedRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        // Fetch the feeds associated with the logged-in user
        // $userFeeds = $userFeedRepository->findBy(['user' => $user]);

        return $this->render('feed/index.html.twig', [
            'user_feeds' => $userFeedRepository->findBy(['user' => $user]),
        ]);
    }

    #[Route('/new', name: 'app_feed_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FeedRepository $feedRepository): Response
    {
        $form = $this->createForm(FeedType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get feed data from the form
            $url = $form->get('url')->getData();;
            $name = $form->get('name')->getData();;

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

            $entityManager->persist($userFeed);
            $entityManager->flush();

            return $this->redirectToRoute('app_feed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('feed/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_feed_show', methods: ['GET'])]
    public function show(Feed $feed): Response
    {
        return $this->render('feed/show.html.twig', [
            'feed' => $feed,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_feed_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Feed $feed, EntityManagerInterface $entityManager, FeedRepository $feedRepository): Response
    {
        $form = $this->createForm(FeedType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            // $entityManager->flush();

            // return $this->redirectToRoute('app_feed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('feed/edit.html.twig', [
            'feed' => $feed,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_feed_delete', methods: ['POST'])]
    public function delete(Request $request, Feed $feed, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $feed->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($feed);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_feed_index', [], Response::HTTP_SEE_OTHER);
    }
}
