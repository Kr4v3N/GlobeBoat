<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminDashboardController
 *
 * @package App\Controller
 */
class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager)
    {

        $users = $manager->createQuery('SELECT COUNT(a) FROM App\Entity\User a')->getSingleScalarResult();
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
