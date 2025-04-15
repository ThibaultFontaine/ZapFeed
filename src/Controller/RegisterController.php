<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController {
	#[Route("/register", name: 'app_register_index', methods:['GET'])]
    public function index(): Response {
        return $this->render('register/index.html.twig');
    }

	#[Route("/register", methods:['POST'])]
    public function register(): Response {
        // return $this->render('auth/register.html.twig');
		return new Response('Registering user...');
    }

	private function hashPassword() {

	}
}