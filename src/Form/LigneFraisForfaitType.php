<?php

namespace App\Form;

use App\Entity\LigneHorsForfait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneFraisForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('quantite', IntegerType::class, [
                'label' => 'Saisissez la quantité ',

            ])
            ->add('libelle', ChoiceType::class, [
                'choices' => [
                    'ETP' => 1,
                    'KM' => 2,
                    'NUI' => 3,
                    'REP' => 4,]

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneHorsForfait::class,
        ]);
    }
}
