<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WeatherMapController extends AbstractController
{
    /**
     * @Route("/weather/map", name="weather_map")
     */
    public function index(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('weather/weatherMap.html.twig');
    }
}
