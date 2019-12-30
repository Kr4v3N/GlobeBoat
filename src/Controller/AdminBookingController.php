<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AdminBookingController
 *
 * @package App\Controller
 */
class AdminBookingController extends AbstractController
{

    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     * @param \App\Repository\BookingRepository $repo
     * @param                                   $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(BookingRepository $repo, $page): \Symfony\Component\HttpFoundation\Response
    {
        $limits = 7;
        $start = $page * $limits - $limits;

        $total = count($repo->findAll());
        $pages = ceil($total / $limits);

        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findBy([], ['createdAt'=> 'DESC'], $limits, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Permet d'éditer une réservation
     *
     * @param \App\Entity\Booking                       $booking
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface      $manager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {

        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n°{$booking->getId()} a bien été modifiée"
            );

            return $this->redirectToRoute('admin_booking_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * Permet de supprimer une réservation
     *
     * @param \App\Entity\Booking                  $booking
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     */
    public function delete(Booking $booking, EntityManagerInterface $manager): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            'La réservation a bien été supprimée'
        );

        return $this->redirectToRoute('admin_booking_index');
    }
}
