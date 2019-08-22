<?php

namespace App\Form;

use App\Entity\Place;
use App\Entity\Situation;
use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
                'label' => 'Date'
            ])
            ->add('inscriptionDate' ,DateTimeType::class, [
                'label' => 'Date d\'inscription',
                'attr'   => [
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
            ->add('status', ChoiceType::class, [
                'choices' => $options['status'],
                'choice_label' => 'libelle',
                'choice_value' => function (Situation $situation = null) {
                    return $situation ? $situation->getId() : '';
                },
                'label' => 'Etat : ',
                'attr' => [
                    'class' => 'browser-default'
                ]
            ])
            ->add('place', ChoiceType::class, [
                'choices' => $options['places'],
                'choice_label' => 'libelle',
                'choice_value' => function (Place $place = null) {
                    return $place ? $place->getId() : '';
                },
                'label' => 'Place : '
            ])
            ->add('coverImage' ,FileType::class, [
                'label' => 'Image : ',
                'data_class' => null,
                // make it optional so you don't have to re-upload the PDF file
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => Trip::class,
            'status'        => null,
            'places'        => null
        ]);
    }
}
