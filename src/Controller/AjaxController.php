<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{
    /**
     * @Route("/ajax", name="ajax")
     */
    public function index()
    {
        return $this->render('ajax/index.html.twig', [
            'controller_name' => 'AjaxController',
        ]);
    }

    /**
     * @Route("/ajax_add_city", name="ajaxAddCity" , methods={"GET","POST"})
     * @param Request $request
     * @param CityRepository $cityRepository
     * @return JsonResponse
     */
    public function ajaxAddCity(Request $request, CityRepository $cityRepository)
    {
        $libelle = $request->get('libelle');
        $zipCode = $request->get('zipCode');

        $city = new City();
        $city->setLibelle($libelle);
        $city->setPostalCode($zipCode);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($city);
        $city->setLibelle(strtoupper($city->getLibelle()));
        $entityManager->flush();


        $cities = $cityRepository->findAll();
        return new JsonResponse($cities);

    }

    /**
     * @Route("/ajax_add_place", name="ajaxAddPlace" , methods={"GET","POST"})
     * @param Request $request
     * @param CityRepository $cityRepository
     * @param PlaceRepository $placeRepository
     * @return JsonResponse
     */
    public function ajaxAddPlace(Request $request, CityRepository $cityRepository, PlaceRepository $placeRepository)
    {

        $city = $cityRepository->find($request->get('city'));
        $place = new Place();

        $place->setLibelle($request->get('libelle'));
        $place->setStreet($request->get('street'));
        $place->setLatitude($request->get('latitude'));
        $place->setLongitude($request->get('longitude'));
        $place->setCity($city);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($place);
        $entityManager->flush();

        $places = $placeRepository->findby(['city' => $city]);
        return new JsonResponse($places);

    }
}