<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 *
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}", name="user_show")
     * @param \App\Entity\User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(User $user): \Symfony\Component\HttpFoundation\Response
    {

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
