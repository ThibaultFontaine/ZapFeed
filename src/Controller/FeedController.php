<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\UserFeed;
use App\Entity\Item;
use App\Form\FeedType;
use App\Repository\FeedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use SimplePie\SimplePie;


// #[Route('/feed')]
final class FeedController extends AbstractController
{
    // #[Route(name: 'app_feed_index', methods: ['GET'])]
    // public function index(FeedRepository $feedRepository): Response
    // {
    //     return $this->render('feed/index.html.twig', [
    //         'feeds' => $feedRepository->findAll(),
    //     ]);
    // }

    // #[Route('/new', name: 'app_feed_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $feed = new Feed();
    //     $form = $this->createForm(FeedType::class, $feed);
    //     $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            // Get feed data from the form
            $url = $formData['url'];
            $name = $formData['name'];

            // Vérifier si un flux avec cette URL existe déjà
            $feed = $feedRepository->findOneBy(['url' => $url]);

            // Si le flux n'existe pas, le créer
            if (!$feed) {
                $feed = new Feed();
                $feed->setUrl($url);
                $entityManager->persist($feed);
                $entityManager->flush(); // Flush pour obtenir l'ID du feed
            }

            // Créer une association UserFeed
            $user = $this->getUser(); // L'utilisateur connecté

            if (!$user) {
                throw $this->createAccessDeniedException('User not logged in');
            }

            $userFeed = new UserFeed();
            $userFeed->setUser($user);
            $userFeed->setFeed($feed);
            $userFeed->setTitle($name);
            $userFeed->setCreatedAt(new \DateTimeImmutable());
            $userFeed->setUpdatedAt(new \DateTime());

            $entityManager->persist($userFeed);
            $entityManager->flush();

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
                }
            }

            return $this->redirectToRoute('app_feed_index', [], Response::HTTP_SEE_OTHER);
        }


    //     return $this->render('feed/new.html.twig', [
    //         'feed' => $feed,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_feed_show', methods: ['GET'])]
    // public function show(Feed $feed): Response
    // {
    //     return $this->render('feed/show.html.twig', [
    //         'feed' => $feed,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_feed_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Feed $feed, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(FeedType::class, $feed);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_feed_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('feed/edit.html.twig', [
    //         'feed' => $feed,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_feed_delete', methods: ['POST'])]
    // public function delete(Request $request, Feed $feed, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $feed->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($feed);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_feed_index', [], Response::HTTP_SEE_OTHER);
    // }
}
