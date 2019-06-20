<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate',
                DateType::class,
                $this->getConfiguration("Date d'arrivée", "La date à laquelle vous comptez arrivé",
                    ["widget" => "single_text"]))

            ->add('endDate', DateType::class,
                $this->getConfiguration("Date de départ", "La date à laquelle vous partez",
                    ["widget" => "single_text"]))

            ->add('comment',
                TextareaType::class,
                $this->getConfiguration(false, "Si vous avez un commentaire, n'hésitez pas à en faire part !",
                ["required" => false]))
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
