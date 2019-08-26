<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libelle',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('latitude', TextType::class, [
                'label' => 'Latitude',
                'invalid_message' => 'La latitude est incorrect',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude',
                'invalid_message' => 'La longitude est incorrect',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('city', ChoiceType::class, [
                'choices' => $options['cities'],
                'choice_label' => 'libelle',
                'choice_value' => function (City $city = null) {
                    return $city ? $city->getId() : '';
                },
                'label' => 'Ville'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
            'cities' => null
        ]);
    }
}
