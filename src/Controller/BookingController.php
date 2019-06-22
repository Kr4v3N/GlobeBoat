<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookingController
 *
 * @package App\Controller
 */
class BookingController extends AbstractController
{
    /**
     * Permet de créer une réservation
     *
     * @param \App\Entity\Ad                             $ad
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted("ROLE_USER")
     *
     * @Route("/ads/{slug}/book", name="booking_create")
     */

    public function book(Ad $ad, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            $booking->setBooker($user)
                    ->setAd($ad);

            // Si les dates ne sont pas disponibles, message d'erreur
            if(!$booking->isBookableDates()) {

                $this->addFlash(
                    'warning',
                    'Les dates que vous avez choisi ne peuvent être réservées : elles sont déjà prises.'
                );

            } else {

                // Sinon enregistrement et redirection
                $manager->persist($booking);
                $manager->flush();

                return $this->redirectToRoute('booking_show', ['id' => $booking->getId(), 'successAlert' => 1]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()

        ]);
    }

    /**
     * Permet d'afficher la page d'une réservation
     *
     * @param Booking $booking
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/booking/{id}", name="booking_show")
     */
    public function show(Booking $booking) {

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * Permet d'afficher la liste des réservations faites par l'utilisateur
     *
     * @Route("/account/bookings", name="account_bookings")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bookings() {
        return $this->render('account/bookings.html.twig');
    }

}
