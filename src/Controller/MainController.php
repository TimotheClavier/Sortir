<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{
    /**
     * @Route("/", name="Index")
     */
    public function index()
    {
        $user = $this->getUser();
        var_dump($user);
        return $this->render('index.html.twig');
    }
}
