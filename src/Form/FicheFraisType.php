<?php

namespace App\Form;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheFraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montantValide', TextType::class, [
                'label' => 'Dernière classe fréquentée',
                'attr' => [
                    'placeholder' => 'Précisez l\'année et la classe fréquenté ',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ]);

    }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([

            ]);
        }

}