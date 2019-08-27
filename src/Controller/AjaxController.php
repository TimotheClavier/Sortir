<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
}
