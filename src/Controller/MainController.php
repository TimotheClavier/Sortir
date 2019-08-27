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
     * @throws DBALException
     */
    public function index(AuthenticationUtils $authenticationUtils,
                          Request $request,
                          PaginatorInterface $paginator,
                          TripRepository $tripRepository,
                          PlaceRepository $placesRepository,
                          CityRepository $cityRepository,
                          EntityManagerInterface $em)
    {
        $res = [];

        dump('toto');
        //die;
        dump($request);
        $user = $this->getUser();
        if ($user !== null) {

            /** @var Trip[] $trips */
            $lesTrips = $tripRepository->findBy([], ['tripDate' => 'DESC']);
            $cities = $cityRepository->findAll();
            $places = $placesRepository->findAll();
            $rawSql = "SELECT user_id , trip_id  FROM users_trips  WHERE user_id = :iduser";

            $stmt = $em->getConnection()->prepare($rawSql);

            $stmt->execute(array('iduser' => $user->getId()));

            $userTrips = $stmt->fetchAll();

            //$trips = array_chunk($lesTrips, 3);

            $em = $this->getDoctrine()->getManager();
//            $allOurBlogPosts = $em->getRepository('App:Trip')->findAll();

            $paginator  = $this->get('knp_paginator');

            $pagination = $paginator->paginate(
                $lesTrips,
                $request->query->getInt('page', 1),
                3
            );

            /*$response = new Response(json_encode(array(
                'pagination' => $pagination,
            )));
            $response->headers->set('Content-Type', 'application/json');*/

            return $this->render('index.html.twig', [
                'trips' => $lesTrips,
                'pagination' => $pagination,
                'userTrips' => $userTrips,
                'user' => $user,
                'cities' => $cities,
                'places' => $places,
                'date' => new \DateTime(),
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
