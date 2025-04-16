<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CatalogueController extends AbstractController
{
    #[Route("/catalogue", methods: ['GET'])]
    public function catalogue(): Response
    {
        return $this->render('catalogue.html.twig');
    }
}