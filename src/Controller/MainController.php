<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends Controller
{
    /**
     * @Route("/", name="Index")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        $user = $this->getUser();
        if ($user !== null) {
            return $this->render('index.html.twig');
        } else {
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('pages/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error
            ]);
        }
    }
}
