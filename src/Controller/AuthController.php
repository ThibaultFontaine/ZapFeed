<?php 

declare(strict_types= 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController{
    #[Route("/login", methods:['GET'])]
    public function home(): Response{
        return $this->render('auth/login.html.twig');
    }
}