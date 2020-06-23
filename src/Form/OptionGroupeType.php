<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\OptionGroupe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OptionGroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un titre'
                    ])
                ]
            ])

            ->add('categorie', EntityType::class, [
                // Obligatoire si EntityType utilisé
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Sélectionner au minimum une catégorie'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OptionGroupe::class,
        ]);
    }
}
