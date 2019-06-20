<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    /**
     * AppFixtures constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        // Creation d'un nouveau rôle
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');

        $manager->persist($adminRole);

        // Creation d'un utilisateurs qui aura le rôle admin
        $adminUser = new User();
        $adminUser->setFirstName('Fayçal')
                ->setLastName('Chena')
                ->setEmail('goldama@gmail.com')
                ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                ->setPicture('https://randomuser.me/api/portraits/men/16.jpg')
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->addUserRole($adminRole);

        $manager->persist($adminUser);

        // Gestion des utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for($i = 1; $i <= 10; $i++) {

            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre === 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // Gestion des annonces
        for($i = 1; $i <= 20; $i++) {

            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join ('</p><p>', $faker->paragraphs(5)).'</p>';
            $departureCity = $faker->state();

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(random_int(10055, 350500))
                ->setRooms(mt_rand(1, 15))
                ->setDepartureCity($departureCity)
                ->setAuthor($user);

            for($j = 1; $j <= mt_rand(3, 6); $j++) {
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }

            // Gestion des réservations
            for($j = 1; $j <= mt_rand(0, 10); $j++) {

                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');
                // Gestion de la date de fin
                $duration  = mt_rand(3, 10);
                $endDate   = (clone $startDate)->modify("+$duration days");

                $amount    =  $ad->getPrice() * $duration;
                $booker    = $users[mt_rand(0, count($users) -1)];
                $comment   = $faker->paragraph();

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($amount)
                    ->setComment($comment);

                $manager->persist($booking);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }

}
