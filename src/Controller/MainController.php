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
        if ($user !== null) {
            $view = "index.html.twig";
        } else {
            $view = "pages/login.html.twig";
        }
        return $this->render($view);
    }
}
