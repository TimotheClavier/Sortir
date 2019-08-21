<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
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
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Doctrine\DBAL\DBALException
     */
    public function index(AuthenticationUtils $authenticationUtils, TripRepository $tripRepository, EntityManagerInterface $em)
    {
        $res = [];
        $user = $this->getUser();
        if ($user !== null) {
            /** @var Trip[] $trips */
            $trips = $tripRepository->findAll();

            $rawSql = "SELECT user_id , trip_id  FROM users_trips  WHERE user_id = :iduser";

            $stmt = $em->getConnection()->prepare($rawSql);

            $stmt->execute(array('iduser' => $user->getId()));

            $userTrips = $stmt->fetchAll();

            dump(count($trips[0]->getUsers()));
            return $this->render('index.html.twig', [
                'trips' => $trips,
                'userTrips' => $userTrips,
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
