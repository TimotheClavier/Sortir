<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use App\Repository\TripRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;

class MainController extends Controller
{
    /**
     * @Route("/", name="Index")
     * @param AuthenticationUtils $authenticationUtils
     * @param TripRepository $tripRepository
     * @param PlaceRepository $placesRepository
     * @param CityRepository $cityRepository
     * @param EntityManagerInterface $em
     * @return Response
     * @throws DBALException
     */
    public function index(AuthenticationUtils $authenticationUtils, TripRepository $tripRepository, PlaceRepository $placesRepository ,CityRepository $cityRepository, EntityManagerInterface $em)
    {
        $res = [];
        $user = $this->getUser();
        if ($user !== null) {
            /** @var Trip[] $trips */
            $trips = $tripRepository->findAll();

            $cities = $cityRepository->findAll();
            $places = $placesRepository->findAll();
            $rawSql = "SELECT user_id , trip_id  FROM users_trips  WHERE user_id = :iduser";

            $stmt = $em->getConnection()->prepare($rawSql);

            $stmt->execute(array('iduser' => $user->getId()));

            $userTrips = $stmt->fetchAll();

            return $this->render('index.html.twig', [
                'trips' => $trips,
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
