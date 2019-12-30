<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

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
    public function login(AuthenticationUtils $utils): \Symfony\Component\HttpFoundation\Response
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
    public function logout(): void
    {}

    /**
     * Permet d'afficher le formulaire d'inscription
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Doctrine\ORM\EntityManagerInterface                                  $manager
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/register", name="account_register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface      $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted("ROLE_USER")
     *
     * @Route("/account/profile", name="account_profile")
     */
    public function profile(Request $request, EntityManagerInterface $manager): \Symfony\Component\HttpFoundation\Response
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
     * Permet de modifier le mot de passe
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     * @param \Doctrine\ORM\EntityManagerInterface                                  $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/account/update-password", name="account-password")
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager): \Symfony\Component\HttpFoundation\Response
    {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                $form->get('oldPassword')->addError(new FormError("le mot de passe saisie n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié !'
                );

                return $this->redirectToRoute('homepage');

            }

        }

        return $this->render('account/password.html.twig', [
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
    public function myAccount(): \Symfony\Component\HttpFoundation\Response
    {

        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * Permet d'afficher la liste des réservations faites par l'utilisateur
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/account/bookings", name="account_bookings")
     */
    public function bookings(): \Symfony\Component\HttpFoundation\Response
    {

        return $this->render('account/bookings.html.twig');
    }

}
