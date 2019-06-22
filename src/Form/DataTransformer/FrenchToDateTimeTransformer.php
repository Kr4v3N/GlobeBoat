<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class FrenchToDateTimeTransformer
 *
 * @package \App\Form\DataTransformer
 */
class FrenchToDateTimeTransformer implements DataTransformerInterface
{

    /**
     * @param $date
     *
     * @return string
     */
    public function transform($date)
    {

        if($date === null)
        {
            return '';
        }

        return $date->format('d/m/Y');
    }

    /**
     * @param $frenchDate
     *
     * @return bool|\DateTime
     */
    public function reverseTransform($frenchDate)
    {
        // frenchDate = 22/06/2019
        if($frenchDate === null) {
            // Exception
            throw new TransformationFailedException('Vous devez fournir une date');
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        if($date === false) {
            // Exception
            throw new TransformationFailedException("Le format de la date n'est pas le bon");
        }

        return $date;
    }

}
