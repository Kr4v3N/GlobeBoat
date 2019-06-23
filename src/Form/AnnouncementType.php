<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Class AdType
 *
 * @package App\Form
 */
class AnnouncementType extends ApplicationType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class, $this->getConfiguration('Titre', "Tapez titre pour votre annonce")
            )
            ->add(
                'slug',
                TextType::class, $this->getConfiguration("Adresse web", "Tapez l'adresse web (automatique)", [
                    'required' => false
                ])
            )
            ->add(
                'coverImage',
                UrlType::class, $this->getConfiguration("URL de l'image principale", "Donnez l'adresse d'une image")
            )
            ->add(
                'introduction',
                TextType::class, $this->getConfiguration("Introduction", "Donnez une description globale de l'annonce")
            )
            ->add(
                'departureCity',
                TextType::class, $this->getConfiguration("Zone de navigation", "indiquez la zone de navigation")
            )
            ->add(
                'content',
                TextareaType::class, $this->getConfiguration("Description détaillée", "Tapez une description")
            )
            ->add(
                'rooms',
                IntegerType::class, $this->getConfiguration("Nombre de cabines", "Le nombre de cabines")
            )
            ->add(
                'price',
                MoneyType::class, $this->getConfiguration("Prix par jour", "Indiquez le prix que vous voulez pour une journée")
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
