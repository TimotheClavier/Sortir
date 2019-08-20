<?php

namespace App\Controller;

use App\Entity\Situation;
use App\Form\SituationType;
use App\Repository\SituationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/situation")
 */
class SituationController extends Controller
{
    /**
     * @Route("/", name="situation_index", methods={"GET"})
     */
    public function index(SituationRepository $situationRepository): Response
    {
        return $this->render('situation/index.html.twig', [
            'situations' => $situationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="situation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $situation = new Situation();
        $form = $this->createForm(SituationType::class, $situation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($situation);
            $entityManager->flush();

            return $this->redirectToRoute('situation_index');
        }

        return $this->render('situation/new.html.twig', [
            'situation' => $situation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="situation_show", methods={"GET"})
     */
    public function show(Situation $situation): Response
    {
        return $this->render('situation/show.html.twig', [
            'situation' => $situation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="situation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Situation $situation): Response
    {
        $form = $this->createForm(SituationType::class, $situation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('situation_index');
        }

        return $this->render('situation/edit.html.twig', [
            'situation' => $situation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="situation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Situation $situation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$situation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($situation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('situation_index');
    }
}
