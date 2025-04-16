<?php

namespace App\Controller;

use App\Entity\UserItem;
use App\Form\UserItemType;
use App\Repository\UserItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/item')]
final class UserItemController extends AbstractController
{
    #[Route(name: 'app_user_item_index', methods: ['GET'])]
    public function index(UserItemRepository $userItemRepository): Response
    {
        return $this->render('user_item/index.html.twig', [
            'user_items' => $userItemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userItem = new UserItem();
        $form = $this->createForm(UserItemType::class, $userItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_item/new.html.twig', [
            'user_item' => $userItem,
            'form' => $form,
        ]);
    }

    #[Route('/{user}', name: 'app_user_item_show', methods: ['GET'])]
    public function show(UserItem $userItem): Response
    {
        return $this->render('user_item/show.html.twig', [
            'user_item' => $userItem,
        ]);
    }

    #[Route('/{user}/edit', name: 'app_user_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserItem $userItem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserItemType::class, $userItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_item/edit.html.twig', [
            'user_item' => $userItem,
            'form' => $form,
        ]);
    }

    #[Route('/{user}', name: 'app_user_item_delete', methods: ['POST'])]
    public function delete(Request $request, UserItem $userItem, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $userItem->getUser(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($userItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
