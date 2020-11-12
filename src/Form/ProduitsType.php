<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\OptionGroupe;
use App\Entity\Options;
use App\Entity\Produits;
// use App\Entity\Tailles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],        
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un titre'
                    ])
                ]
            ])
            ->add('description', CKEditorType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],        
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un description'
                    ])
                ]
            ])
            ->add('prix', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],        
                'scale' => 2,
                'rounding_mode' => 0
            ])
            ->add('images', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],        
                'required' => false,
                'multiple' => true,
            ])
            ->add('image_choisi', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],        
                'required' => false,
                'data_class' => null
            ])
            ->add('colors', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],        
                'choices'  => [
                    'rouge' => "rouge",
                    'jaune' => "jaune",
                    'vert' => "vert",
                    'orange' => "orange",
                    'noire' => "noire",
                    'blanc' => "blanc",
                    'bleu' => "bleu",
                    'rose' => "rose",
                    'gris' => "gris",
                    'violet' => "violet",
                    'bordeaux' => "bordeaux",
                    'brun' => "brun",
                    'doré' => "doré",
                    'biege' => "biege",
                    'jean' => "jean",
                    'ombré' => "ombré",
                    'multicolors' => "multicolors"  
                ],
                'placeholder' => 'Selectionner une couleur',
                'multiple' => true,
                'required'   => false,
            ])
            ->add('categorie', EntityType::class, [
                // Obligatoire si EntityType utilisé
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'placeholder' => 'Selectionner une categorie',
                'attr' => [
                    'class' => 'form-control'
                ],        
                // ---
                'constraints' => [
                    new NotBlank([
                        'message' => 'Sélectionner au minimum une catégorie'
                    ])
                ]
            ])

            ->add('optionGroupe',EntityType::class,[
                'class' => OptionGroupe::class,
                'placeholder' => 'sélectionnez votre option groupe',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]     
            ]);
            $builder->get('optionGroupe')->addEventListener(
                FormEvents::POST_SUBMIT,
                function(FormEvent $event) {
                    $form = $event->getForm();
                    $this->addOptionField($form->getParent(), $form->getData());
                }
            );
            $builder->addEventListener(
                FormEvents::POST_SET_DATA,
                function(FormEvent $event){
                    $data = $event->getData();
                    /* @var $ville Ville */
                    $taille = $data->getTailles();
                    $form = $event->getForm();
                    if ($taille) {
                        $option = $taille->getOptions();
                        $optionGroupe = $option->getOptionGroupe();
                        $this->addOptionField($form, $optionGroupe);
                        $this->addtailleField($form, $option);
                        $form->get('optionGroupe')->setData($optionGroupe);
                        $form->get('option')->setData($option);
                    }else{
                        $this->addOptionField($form, null);
                        $this->addTailleField($form, null);
                    }
                }
            );       
    }        

    /**
     * Rajoute un champs Option au formulaire
     * @param FormInterface $form
     * @param OptionGroupe $optionGroup 
     */
    private function addOptionField(FormInterface $form, ?OptionGroupe $optionGroupe){
        
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'options',
            EntityType::class,
            null,
            [
                'class' => Options::class,
                'placeholder' => $optionGroupe ? 'sélectionnez une option' : 'sélectionnez une option groupe',
                'mapped' => false,
                'required' => false,
                'auto_initialize' => false,
                'attr' => [
                    'class' => 'form-control'
                ],        
                'choices' => $optionGroupe ? $optionGroupe->getOptions() : []
            ]
            );
            $builder->addEventListener(
                         FormEvents::POST_SUBMIT,
                function(FormEvent $event){
                    $form = $event->getForm();
                    $this->addTailleField($form->getParent(), $form->getData());
                }
            );
            $form->add($builder->getForm());
    }

    // private function addTailleField(FormInterface $form, ?Options $option){
    //     $form->add('tailles', EntityType::class,[
    //         'class' => Tailles::class,
    //         'placeholder' => $option ? 'sélectionnez votre taille' : 'sélectionnez votre option',
    //         'attr' => [
    //             'class' => 'form-control'
    //         ],        
    //         'choices' => $option ? $option->getTailles()->getNom() : []
    //     ]);
    // }


    private function addTailleField(FormInterface $form, ?Options $options){
        if($options == "Robe" || $options == "T-shirts" || $options == "Blouses" || 
           $options == "Monteaux&Vestes" ||$options == "Pantalons" ||
           $options == "Jupes" || $options == "Shorts" || $options == "Vêtements de plage" ||
           $options == "Chemises" || $options == "Polos" || $options == "Maillots de bain" ){
            $form->add('tailles', ChoiceType::class,[
                'choices' => [
                //  $options ? $options->getTailles() : [],
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
            'attr' => [
                'class' => 'form-control'
            ]
            ]);   
        }
        elseif ($options == "chausseurs"){
            $form->add('tailles', ChoiceType::class,[
                'choices' => [
                    // $options ? $options->getTailles() : [],
                    '35' => "35",
                    '36' => "36",
                    '37' => "37",
                    '38' => "38",
                    '39' => "39",
                    '40' => "40",
                    '41' => "41"
            ],
            'placeholder' => 'Selectionner une taille',
            'multiple' => true,
            'required'   => false,
            'attr' => [
                'class' => 'form-control'
            ]
            ]);   
        }
        else{
            $form->add('tailles', ChoiceType::class,[
                'choices' => [
                    // $options ? $options->getTailles() : [],
                    'taille unique' => 'taille unique'
            ],
            'placeholder' => 'Selectionner une taille',
            'multiple' => true,
            'required'   => false,
            'attr' => [
                'class' => 'form-control'
            ]
            ]);   

        }

    }

        
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}

