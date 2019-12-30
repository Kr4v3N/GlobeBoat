<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AdminDashboardController
 *
 * @package App\Controller
 */
class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(EntityManagerInterface $manager): \Symfony\Component\HttpFoundation\Response
    {

        $users = $manager->createQuery('SELECT COUNT(a) FROM App\Entity\User a')->getSingleScalarResult();
//        dump($users);
        $ads = $manager->createQuery('SELECT COUNT(b) FROM App\Entity\Ad b')->getSingleScalarResult();
        $bookings = $manager->createQuery('SELECT COUNT(c) FROM App\Entity\Booking c')->getSingleScalarResult();
        $comments= $manager->createQuery('SELECT COUNT(d) FROM App\Entity\Comment d')->getSingleScalarResult();

        $bestAds = $manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER by note DESC'
        )
        ->setMaxResults(7)
        ->getResult();

        $worstAds = $manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER by note ASC'
        )
        ->setMaxResults(7)
        ->getResult();

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => [
                'users' => $users,
                'ads' => $ads,
                'bookings' => $bookings,
                'comments' => $comments,
            ],
                'bestAds'=> $bestAds,
                'worstAds' => $worstAds

        ]);
    }
}
