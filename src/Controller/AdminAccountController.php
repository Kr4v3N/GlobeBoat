<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class AdminAccountController
 *
 * @package App\Controller
 */
class AdminAccountController extends AbstractController
{
    /**
     * Permet de ce connecter
     *
     * @Route("/admin/login", name="admin_account_login")
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $utils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $utils): \Symfony\Component\HttpFoundation\Response
    {

        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @return void
     *
     * @Route("/admin/logout", name="admin_account_logout")
     */
    public function logout(): void
    {
        // ...
    }
}
