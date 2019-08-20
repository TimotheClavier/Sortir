<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends Controller
{
    /**
     * @Route("/", name="Index")
     * @param AuthenticationUtils $authenticationUtils
     * @param TripRepository $tripRepository
     * @return Response
     */
    public function index(AuthenticationUtils $authenticationUtils, TripRepository $tripRepository)
    {
        $user = $this->getUser();
        if ($user !== null) {
            /** @var Trip[] $trips */
            $trips = $tripRepository->findAll();
            dump(count($trips[0]->getUsers()));
            return $this->render('index.html.twig', [
                'trips' => $trips
            ]);
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
