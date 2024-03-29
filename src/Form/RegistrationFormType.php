<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control ',
                    'required'=>'required',
                    'autofocus'=>'autofocus'
                ],
                'label' => 'Nom'
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control ',
                    'required'=>'required'
                ],
                'label' => 'Prénom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail :',
                'attr' => [
                    'class' => 'form-control col'
                ]
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone :',
                'attr' => [
                    'class' => 'form-control col'
                ]
            ])
            ->add('city', ChoiceType::class, [
                'choices'  => $options['cities'],
                'choice_label' => "libelle",
                "choice_value" => "id",
                "attr" => [
                    'class' =>"form-control w-50",
                ],
                'label' => 'Ville :'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe fournis ne correspondent pas',
                'required' => true,
                'first_options' => array('attr' => ['class' => 'form-control col'],'label'=>'Mot de passe :'),
                'second_options' => array('attr' => ['class' => 'form-control col'], 'label'=>'Confirmation :'),
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => User::class,
            'cities'        => City::class
        ]);
    }
}
