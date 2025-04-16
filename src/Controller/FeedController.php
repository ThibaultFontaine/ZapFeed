<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Form\FeedType;
use App\Repository\FeedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($feed);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_feed_index', [], Response::HTTP_SEE_OTHER);
    //     }

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
