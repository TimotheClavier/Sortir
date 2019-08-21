<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Situation;
use App\Entity\Place;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\TripType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\UploadUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/trip")
 */
class TripController extends Controller
{
    /**
     * @Route("/", name="trip_index", methods={"GET"})
     * @param TripRepository $tripRepository
     * @return Response
     */
    public function index(TripRepository $tripRepository): Response
    {
        return $this->render('trip/index.html.twig', [
            'trips' => $tripRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="trip_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $trip = new Trip();
        $status = $this->getDoctrine()->getManager()
            ->getRepository(Situation::class)->findAll();
        $places = $this->getDoctrine()->getManager()
            ->getRepository(Place::class)->findAll();
        $form = $this->createForm(TripType::class, $trip,[ 'status' => $status , 'places' => $places]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trip->setOrganizer($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->redirectToRoute('trip_index');
        }

        return $this->render('trip/new.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="trip_show", methods={"GET"})
     * @param Trip $trip
     * @return Response
     */
    public function show(Trip $trip): Response
    {
        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
        ]);
    }


    /**
     * @Route("/registration/{id}",  name="trip_registration", methods={"GET"})
     * @param Trip $trip
     * @param Security $security
     * @return Response
     */
    public function  registration(Trip $trip, Security $security )
    {

        $entityManager = $this->getDoctrine()->getManager();
        $trip = $entityManager->getRepository(Trip::class)->find($trip);


        $user = $this->getUser();


        $trip->setSeat($trip->getSeat() - 1);


        $trip->addUser($user);
        $user->addTrip($trip);

        $entityManager->flush();

        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
        ]);
    }


    /**
     * @Route("/cancel/{id}",  name="trip_cancel", methods={"GET"})
     * @param Trip $trip
     * @param Security $security
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Doctrine\DBAL\DBALException
     */
    public function cancel(Trip $trip, Security $security,EntityManagerInterface $em)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $trip = $entityManager->getRepository(Trip::class)->find($trip);


        $user = $this->getUser();

        $rawSql = "DELETE FROM users_trips  WHERE user_id = :iduser AND trip_id = :idtrip";

        $stmt = $em->getConnection()->prepare($rawSql);

        $stmt->execute(array('iduser' => $user->getId(),'idtrip' => $trip->getId()));

        $trip->setSeat($trip->getSeat() + 1);

        $entityManager->flush();

        return $this->redirectToRoute('Index', []);

    }


    /**
     * @Route("/{id}/edit", name="trip_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Trip $trip
     * @return Response
     */
    public function edit(Request $request, Trip $trip): Response
    {
        $status = $this->getDoctrine()->getRepository(Situation::class)->findAll();
        $places = $this->getDoctrine()->getRepository(Place::class)->findAll();

        $form = $this->createForm(TripType::class, $trip,['status'=>$status,'places' => $places]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $upload = new UploadUtils();
            $img = $form['coverImage']->getData();

            if($img)
            {
                $fileName = $upload->upload($img,$this->getParameter('trips_pictures'));
                $trip->setCoverImage('trips/'.$fileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trip_index');
        }

        return $this->render('trip/edit.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trip_delete", methods={"DELETE"})
     * @param Request $request
     * @param Trip $trip
     * @return Response
     */
    public function delete(Request $request, Trip $trip): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trip->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trip_index');
    }
}
