<?php

namespace App\Form;

use App\Entity\LigneHorsForfait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneFraisForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('libelleETP', IntegerType::class, [
                'label' => 'Saisissez les frais Etapes',

            ])
            ->add('libelleKM', IntegerType::class, [
                'label' => 'Saisissez les frais kilométriques',

            ])
            ->add('libelleNUI', IntegerType::class, [
                'label' => 'Saisissez les frais nuités ',

            ])
            ->add('libelleREP', IntegerType::class, [
                'label' => 'Saisissez les frais REP',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
