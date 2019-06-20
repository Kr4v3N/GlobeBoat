<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * Permet
     *
     * @param \App\Entity\Ad $ad
     * @return \Symfony\Component\HttpFoundation\Response
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted("ROLE_USER")
     * @Route("/ads/{slug}/book", name="booking_create")
     */

    public function book(Ad $ad)
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()

        ]);
    }
}
