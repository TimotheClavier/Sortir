<?php

namespace App\Form;

use App\Entity\Place;
use App\Entity\Situation;
use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('tripDate', DateTimeType::class)
            ->add('inscriptionDate' ,DateTimeType::class)
            ->add('seat')
            ->add('duration')
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
