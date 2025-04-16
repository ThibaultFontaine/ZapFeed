<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/about')]
class AboutController extends AbstractController
{
    #[Route(name: 'app_about', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('about.html.twig');
    }
}
