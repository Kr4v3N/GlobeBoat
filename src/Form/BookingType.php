<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BookingType
 *
 * @package App\Form
 */
class BookingType extends ApplicationType
{

    private $transformer;

    /**
     * BookingType constructor.
     *
     * @param \App\Form\DataTransformer\FrenchToDateTimeTransformer $transformer
     */
    public function __construct(FrenchToDateTimeTransformer $transformer){

        $this->transformer = $transformer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('startDate',
                TextType::class,
                $this->getConfiguration("Date d'arrivée", 'La date à laquelle vous comptez arrivé'))

            ->add('endDate',
                TextType::class,
                $this->getConfiguration('Date de départ', 'La date à laquelle vous partez'))

            ->add('comment',
                TextareaType::class,
                $this->getConfiguration(false, "Si vous avez un commentaire, n'hésitez pas à en faire part !",
                ['required' => false]))
        ;

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => Booking::class,
            'validation_groups' => [
                'Default',
                'front'
            ]
        ]);
    }
}
