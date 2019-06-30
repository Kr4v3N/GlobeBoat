<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WeatherMapController extends AbstractController
{
    /**
     * @Route("/weather/map", name="weather_map")
     */
    public function index()
    {
        return $this->render('weather/weatherMap.html.twig');
    }
}
