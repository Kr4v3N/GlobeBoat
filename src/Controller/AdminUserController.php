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
     * @Route("/admin/users", name="admin_user_index")
     */
    public function index(UserRepository $repo)
    {

        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAll()
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
                "L'utilisateur n°{$user->getId()} a bien été modifié"
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
        if(count($user->getBookings()) > 0)
        {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'utilisateur <strong> {$user->getLastName()} </strong> car il possède des réservations"
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
