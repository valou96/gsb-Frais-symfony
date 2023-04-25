<?php

namespace App\Form;

use App\Entity\FicheFrais;
use IntlDateFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheFraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mois', ChoiceType::class,[
                'choices' => [$options['mois'],
                ],
                'choice_label' => function ($choice, $key, $value){
                $dateObj = \DateTime::createFromFormat('Ym', $value);
                $fmt = datefmt_create(
                    'fr_FR',
                    IntlDateFormatter::FULL,
                    IntlDateFormatter::FULL,
                    'Europe/Paris',
                    IntlDateFormatter::GREGORIAN,
                    'MMMM YYYY'
                );
                return datefmt_format($fmt, $dateObj);
                },
                'label' => 'Mois',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mois' =>[],
        ]);
    }
}
