<?php

namespace App\Form;

use App\Entity\Place;
use App\Entity\Situation;
use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('tripDate', DateTimeType::class,[
                'attr' => [
                    'class' => 'browser-default'
                ]
            ])
            ->add('inscriptionDate' ,DateTimeType::class,[
                'attr' => [
                    'class' => 'browser-default'
                ]

    ])
            ->add('seat')
            ->add('duration')
            ->add('description')
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
