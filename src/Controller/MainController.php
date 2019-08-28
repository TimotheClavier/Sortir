<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use App\Repository\TripRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class MainController extends Controller
{
    /**
     * @Route("/", name="Index")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param TripRepository $tripRepository
     * @param PlaceRepository $placesRepository
     * @param CityRepository $cityRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(AuthenticationUtils $authenticationUtils,
                          Request $request,
                          PaginatorInterface $paginator,
                          TripRepository $tripRepository,
                          PlaceRepository $placesRepository,
                          CityRepository $cityRepository,
                          EntityManagerInterface $em)
    {
        $user = $this->getUser();
        if ($user !== null) {

            /** @var Trip[] $trips */
            $lesTrips = $tripRepository->findBy([], ['tripDate' => 'DESC']);
            $cities = $cityRepository->findBy([], ['libelle' => 'ASC']);
            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate($lesTrips, $request->query->getInt('page', 1),3);

            return $this->render('index.html.twig', [
                'pagination' => $pagination,
                'cities' => $cities
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
