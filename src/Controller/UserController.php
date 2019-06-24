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
     */
    public function index(User $user)
    {

        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}
