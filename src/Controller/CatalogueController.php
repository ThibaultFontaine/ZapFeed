<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\FeedRepository;

#[Route("/catalogue")]
class CatalogueController extends AbstractController
{
    #[Route(name: "app_catalogue", methods: ['GET'])]
    public function index(FeedRepository $feedRepository): Response
    {
        return $this->render('catalogue.html.twig', [
            'feeds' => $feedRepository->findAll(),
            'currentPage' => 1,
            'totalPages' => 10,
        ]);
    }
}