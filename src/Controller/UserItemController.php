<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserItemController extends AbstractController
{
    #[Route('/user/item', name: 'app_user_item')]
    public function index(): Response
    {
        return $this->render('user_item/index.html.twig', [
            'controller_name' => 'UserItemController',
        ]);
    }
}
