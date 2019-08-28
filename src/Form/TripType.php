<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Situation;
use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('tripDate', DateTimeType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('inscriptionDate' ,DateTimeType::class, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',
                'attr'   => [
                    'class' => 'form-control',
                    'min' => ( new \DateTime() )->format('Y-m-d H:i:s')
                ]
            ])
            ->add('seat', NumberType::class, [
                'label' => 'Places',
                'invalid_message' => 'Places incorrect',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée (en minutes)',
                'invalid_message' => 'La durée est incorrect',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'md-textarea form-control'
                ]
            ])
            ->add('city', ChoiceType::class, [
                'choices' => $options['cities'],
                'mapped' => false,
                'choice_label' => 'libelle',
                'choice_value' => function (City $city = null) {
                    return $city ? $city->getId() : '';
                },
                'label' => 'Ville :',
            ])
            ->add('place', ChoiceType::class, [
                'label' => 'Site :'
            ])
            ->add('coverImage' ,FileType::class, [
                'label' => null,
                'data_class' => null,
                'attr' => [
                    'class' => 'file-upload'
                ],
                // make it optional so you don't have to re-upload the file
                'mapped' => false,
                // everytime you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Choisissez un format valide.',
                    ])
                ],
            ])
            ->add('save', SubmitType::class,
                [
                    'label' => 'Confirmer',
                    'attr' => [
                        'class'=>'btn btn-success',
                    ]
                ])
            ->add('publish', SubmitType::class,
                [
                    'label' => 'Publier',
                    'attr' => [
                        'class'=>'btn btn-outline-info'
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => Trip::class,
            'status'        => null,
            'cities'        => null
        ]);
    }
}
