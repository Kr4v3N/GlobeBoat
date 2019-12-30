<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnouncementType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AdminAdController
 *
 * @package App\Controller
 */
class AdminAdController extends AbstractController
{

    /**
     * @param int                          $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @param \App\Repository\AdRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */


    public function index(AdRepository $repo, $page): \Symfony\Component\HttpFoundation\Response
    {
        $limits = 7;
        $start = $page * $limits - $limits;

        $total = count($repo->findAll());
        $pages = ceil($total / $limits);

        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findBy([], ['id'=> 'DESC'], $limits, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @param \App\Entity\Ad                            $ad
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface      $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager): \Symfony\Component\HttpFoundation\Response
    {

        $form = $this->createForm(AnnouncementType::class, $ad);

        $form->handleRequest($request);
//        dd($ad);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée"
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @param \App\Entity\Ad                       $ad
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     */
    public function delete(Ad $ad, EntityManagerInterface $manager): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        if(count($ad->getBookings()) > 0)
        {

            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déjà des réservations !"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }


}
