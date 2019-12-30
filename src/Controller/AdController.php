<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnouncementType;
use App\Repository\AdRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AdController
 *
 * @package App\Controller
 */
class AdController extends AbstractController
{
    /**
     * Permet d'afficher la liste des annonces de yachts en location
     *
     * @param \App\Repository\AdRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("ads/{page<\d+>?1}", name="ads_index")
     */
    public function index(AdRepository $repo, $page): \Symfony\Component\HttpFoundation\Response
    {
        $limits = 6;
        $start = $page * $limits - $limits;

        $total = count($repo->findAll());
        $pages = ceil($total / $limits);

        return $this->render('ad/index.html.twig', [
            'ads' => $repo->findBy([], [], $limits, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Permet de créer une annonce
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface      $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted("ROLE_USER")
     * @Route("/ads/new", name="ads_create")
     */
    public function create(Request $request, EntityManagerInterface $manager): \Symfony\Component\HttpFoundation\Response
    {
        $ad = new Ad();

        $form = $this->createForm(AnnouncementType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Persister les images ajoutées appartenants à l'annonce
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @param \App\Entity\Ad                            $ad
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface      $manager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()")
     * @Route("/ads/{slug}/edit", name="ads_edit")
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {

        $form = $this->createForm(AnnouncementType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Persister les images ajoutées appartenants à l'annonce
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @param \App\Entity\Ad $ad
     *
     * @Route("/ads/{slug}", name="ads_show")
     */
    public function show(Ad $ad)
    {

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message="Vous n'avez pas le droit d'accéder à cette ressource")
     * @param Ad                                   $ad
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/ads/{slug}/delete", name="ads_delete")
     */
    public function delete(Ad $ad, EntityManagerInterface $manager) {

        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute('ads_index');
    }

}
