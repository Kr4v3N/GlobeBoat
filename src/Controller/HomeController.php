<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomeController
 *
 * @package App\Controller
 */
class HomeController extends Controller
{
    /**
     * Permet d'afficher la page d'accueil du site
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="homepage")
     */
    public function home(){
        return $this->render('home.html.twig');
    }
}

?>
