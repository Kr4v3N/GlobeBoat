<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * Permet d'afficher la page d'accueil du site
     *
     * @param \App\Repository\AdRepository   $adRepo
     * @param \App\Repository\UserRepository $userRepo
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo): \Symfony\Component\HttpFoundation\Response
    {

        return $this->render('home.html.twig', [
            'ads' => $adRepo->findBestAds(3),
            'users' => $userRepo->findBestUsers(3),

        ]);
    }

    /**
     * @return string
     *
     * @Route("/mentionslegals", name="mentions_legals")
     */
    public function mentionsLegals(): string
    {
        return $this->render('mentionsLegals.html.twig');
    }
}

