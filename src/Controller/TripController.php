<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Situation;
use App\Entity\Place;
use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use App\Repository\SituationRepository;
use App\Repository\TripRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\UploadUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/sorties")
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
        $cities = $this->getDoctrine()->getManager()
            ->getRepository(City::class)->findAll();
        $form = $this->createForm(TripType::class, $trip,
            [ 'status' => $status , 'cities' => $cities]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trip->setOrganizer($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();

            $upload = new UploadUtils();
            $img = $form['coverImage']->getData();

            if($img)
            {
                $fileName = $upload->upload($img,$this->getParameter('trips_pictures'));
                $trip->setCoverImage('img/trips/'.$fileName);
            }

            $created = $entityManager->getRepository(Situation::class)->find(1);
            $published = $entityManager->getRepository(Situation::class)->find(2);

            $tripStatus = $form->get('publish')->isClicked() ? $published : $created;
            $trip->setStatus($tripStatus);

            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('Success', 'Modifications enregistrées !');

            return $this->redirectToRoute('Index');
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
        $this->addFlash('Success', 'Vous êtes inscrit !!');

        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
        ]);
    }


    /**
     * @Route("/unsubscrib/{id}",  name="trip_unsubscrib", methods={"GET"})
     * @param Trip $trip
     * @param Security $security
     * @param EntityManagerInterface $em
     * @return Response
     * @throws DBALException
     */
    public function unsubscrib(Trip $trip, Security $security,EntityManagerInterface $em)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $trip = $entityManager->getRepository(Trip::class)->find($trip);

        $user = $this->getUser();

        $rawSql = "DELETE FROM users_trips  WHERE user_id = :iduser AND trip_id = :idtrip";

        $stmt = $em->getConnection()->prepare($rawSql);

        $stmt->execute(array('iduser' => $user->getId(),'idtrip' => $trip->getId()));

        $trip->setSeat($trip->getSeat() + 1);

        $entityManager->flush();
        $this->addFlash('Success', "Vous n'êtes plus inscrit !!");

        return $this->redirectToRoute('Index', []);

    }

    /**
     * @Route("/cancel/{id}", name="trip_cancel")
     * @param Trip $trip
     * @param SituationRepository $situationRepository
     * @param Request $request
     * @return Response
     */
    public function cancel(Trip $trip, SituationRepository $situationRepository, Request $request)
    {
        $canceled = $situationRepository->find(6);
        $reason = $request->get('reason');
        $trip->setStatus($canceled);
        $trip->setCause($reason);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash('Success', 'Modifications enregistrées !');
        return $this->redirectToRoute('Index');
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

        $form = $this->createForm(TripType::class, $trip,
            ['status'=>$status,'places' => $places]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $upload = new UploadUtils();
            $img = $form['coverImage']->getData();

            if($img)
            {
                $fileName = $upload->upload($img,$this->getParameter('trips_pictures'));
                $trip->setCoverImage('img/trips/'.$fileName);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('Success', 'Modifications enregistrées !');


            return $this->redirectToRoute('Index');
        }

        return $this->render('trip/edit.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajax_request_place", name="ajax_request_place", methods={"GET","POST"})
     * @param Request $request
     * @param CityRepository $cityRepository
     * @param PlaceRepository $placeRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function getPlace(Request $request, CityRepository $cityRepository ,
                             PlaceRepository $placeRepository)
    {
        $city = $cityRepository->findBy(['id'=> $request->get('city')]);
        $places = $placeRepository->findBy(['city'=> $city]);

        return new JsonResponse($places);
    }

    /**
     * @Route("/{id}", name="trip_delete")
     * @param Request $request
     * @param Trip $trip
     * @return Response
     */
    public function delete(Request $request, Trip $trip): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trip->getId(),
            $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trip);
            $entityManager->flush();
            $this->addFlash('Success', 'Modifications enregistrées !');
        }

        return $this->redirectToRoute('trip_index');
    }

}
