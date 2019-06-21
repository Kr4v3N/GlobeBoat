<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountController
 *
 * @package App\Controller
 */
class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     *
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $utils
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        // Si erreur, récupère la dernière
        $error = $utils->getLastAuthenticationError();
        // Recuperer le dernier nom d'utilisateur envoyé
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @return void
     *
     * @Route("/logout", name="account_logout")
     */
    public function logout() {}

    /**
     * Permet d'afficher le formulaire d'inscription
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Doctrine\Common\Persistence\ObjectManager                            $manager
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/register", name="account_register")
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher et de traiter le formulaire de modification de profil
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted("ROLE_USER")
     *
     * @Route("/account/profile", name="account_profile")
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les données du profil ont été enregistrée avec succès !'
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted("ROLE_USER")
     *
     * @Route("/account", name="account_index")
     */
    public function myAccount()
    {

        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

}
