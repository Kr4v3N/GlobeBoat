<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class AdminUserController
 *
 * @package App\Controller
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users/{page<\d+>?1}", name="admin_user_index")
     */
    public function index(UserRepository $repo, $page)
    {
        $limits = 8;
        $start = $page * $limits - $limits;

        $total = count($repo->findAll());
        $pages = ceil($total / $limits);

        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findBy([], [], $limits, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Permet d'éditer le profil d'un utilisateur
     *
     * @param \App\Entity\User                        $user
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/users/{id}/edit", name="admin_user_edit")
     */
    public function edit(User $user, Request $request, ObjectManager $manager)
    {

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur {$user->getFullName()} a bien été modifié"
            );

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * Permet de supprimer un utilisateur
     *
     * @param \App\Entity\User                        $user
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/admin/users/{id}/delete", name="admin_user_delete")
     */
    public function delete(User $user, ObjectManager $manager)
    {
        if (count($user->getBookings()) > 0)
        {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'utilisateur <strong> {$user->getFullName()} </strong> car il possède au moins une réservation"
            );
        }
        elseif (count($user->getAds()) > 0)
        {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'utilisateur <strong> {$user->getFullName()} </strong> car il possède au moins une annonce"
            );
        }else
        {
            $manager->remove($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur a bien été supprimé"
            );
        }
        return $this->redirectToRoute('admin_user_index');
    }
}
