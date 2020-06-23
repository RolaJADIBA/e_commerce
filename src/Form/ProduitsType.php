<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\OptionGroupe;
use App\Entity\Options;
use App\Entity\Produits;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProduitsType extends AbstractType
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
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un titre'
                    ])
                ]
            ])
            ->add('prix', MoneyType::class, [
                'scale' => 2,
                'rounding_mode' => 0
            ])
            ->add('images', FileType::class, [
                'required' => false,
                'multiple' => true,
            ])
            ->add('image_choisi', FileType::class, [
                'required' => false,
                'data_class' => null
                // 'multiple' => true,
            ])
            ->add('colors', ChoiceType::class, [
                'choices'  => [
                    'rouge' => "#B22222ll",
                    'jaune' => "#FFFF00",
                    'vert' => "#008000",
                    'orange' => "FFA500",
                    'noire' => "#000000",
                    'blanc' => "#FFFFFF",
                    'bleu' => "#0000FF",
                    'rose' => "#FF007F",
                    'gris' => "#808080",
                    'violet' => "#EE82EE"
                ],
                'placeholder' => 'Selectionner une couleur',
                'multiple' => true,
                'required'   => false,
            ])
            // ->add('tailles', ChoiceType::class, [
                
            //     'choices' => [
            //         'x-smalle' => "XS",
            //         'smalle' => "S",
            //         'mediume' => "M",
            //         'lage' => "L",
            //         'x-large' => "XL",
            //         'xx-large' => "XXL",
            //         'xxx-large' => "XXXL"
            //     ],
            //     'placeholder' => 'Selectionner une taille',
            //     'multiple' => true,
            //     'required'   => false,
            // ])
            ->add('categorie', EntityType::class, [
                // Obligatoire si EntityType utilisé
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'placeholder' => 'Selectionner une categorie',
                // ---
                'constraints' => [
                    new NotBlank([
                        'message' => 'Sélectionner au minimum une catégorie'
                    ])
                ]
            ])
            // ->add('options', EntityType::class, [
            //     // Obligatoire si EntityType utilisé
            //     'class' => Options::class,
            //     'choice_label' => 'nom',
            //     'placeholder' => 'Selectionner une option',
            //     'attr' =>['class' => 'form-control'],
            //     // ---
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Sélectionner au minimum une option'
            //         ])
            //     ]
            // ])
            ->add('optionGroupe', EntityType::class, [
                'class'       => OptionGroupe::class,
                'placeholder' => 'Sélectionnez votre option groupe',
                'mapped'      => false,
                'required'    => false
              ]);
        $builder->get('optionGroupe')->addEventListener(
                     FormEvents::POST_SUBMIT,
            function(FormEvent $event){
                $form = $event->getForm();
                $form->getParent()->add('options',EntityType::class, [
                    'class'=> Options::class,
                    'placeholder' => 'Sélectionnez votre option',
                    'mapped' => false,
                    'required' => false,
                    'choices' => $form->getData()->getOptions() 
                ]);
            }
        )
        ;

        // $formModifier = function (FormInterface $form, OptionGroupe $optionGroupe = null) {
        //     $option = null === $optionGroupe ? [] : $optionGroupe->getOptions();

        //     $form->add('options', EntityType::class, [
        //         'class' => Options::class,
        //         'required'=>false,
        //         'placeholder' => 'Selectionner une option',
        //         'label' => "Option",
        //         'choices' => $option,
        //         'choice_label' => 'nom',
        //     ]);
        // };

        // $builder->addEventListener(
        //     FormEvents::PRE_SET_DATA,
        //     function (FormEvent $event) use ($formModifier) {
        //         $data = $event->getData();
        //         $formModifier($event->getForm(), $data->getOptionGroupe());
        //     }
        // );

        // $builder->get('optionGroupe')->addEventListener(
        //     FormEvents::POST_SUBMIT,
        //     function (FormEvent $event) use ($formModifier){ 
        //         $optionGroupe = $event->getForm()->getData();
        //         $formModifier($event->getForm()->getParent(), $optionGroupe);
        //     }
        // );



        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $option = $event->getData();
            $form = $event->getForm();

            //if option is sacs ou bijoux => taille is null,
            //if option is chausseur => taille is pointures 35----42
            //else => taille are (XS - S - M - L - XL - XXL)
    
            if (($option->getOptionGroupe() == 'sacs' || $option->getOptionGroupe() == 'Bijoux' || $option->getOptionGroupe() == 'Lunette de soleil') ) {
                return null;
            }
            elseif ($option->getOptionGroupe() == 'chausseurs'){
                $form ->add('tailles', ChoiceType::class, [
                    'choices' => [
                        '35' => "35",
                        '36' => "36",
                        '37' => "37",
                        '38' => "38",
                        '39' => "39",
                        '40' => "40",
                        '41' => "41"
                    ],
                    'placeholder' => 'Selectionner une ponture',
                    'multiple' => true,
                    'required'   => false,
                    'attr' =>['class' => 'form-control']
                ]);
            }
            else{
               $form ->add('tailles', ChoiceType::class, [
                    'choices' => [
                        'x-smalle' => "XS",
                        'smalle' => "S",
                        'mediume' => "M",
                        'lage' => "L",
                        'x-large' => "XL",
                        'xx-large' => "XXL",
                        'xxx-large' => "XXXL"
                    ],
                    'placeholder' => 'Selectionner une taille',
                    'multiple' => true,
                    'required'   => false,
                    'attr' =>['class' => 'form-control']
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
