<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\User;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $profile = $options['profile'];

        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control w-50',
                    'required'=>'required',
                    'autofocus'=>'autofocus',
                    'value' => $profile->getNom()
                ],
                'label' => 'Nom :'
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control  w-50',
                    'required'=>'required',
                    'value' => $profile->getPrenom()

                ],
                'label' => 'Prénom :'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail :',
                'attr' => [
                    'class' => 'form-control w-50',
                    'value' => $profile->getEmail()

                ]
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone :',
                'attr' => [
                    'class' => 'form-control w-50',
                    'value' => $profile->getTelephone()

                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Erreur les mot de passe ne corréspondent pas',
                'required' => false,
                'first_options' => array('attr' => ['class' => 'form-control col w-50'],'label'=>'Mot de passe :'),
                'second_options' => array('attr' => ['class' => 'form-control col w-50'], 'label'=>'Confirmation :'),
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control w-50'
                ]
            ])
            ->add('city')
            ->add('avatar' ,FileType::class, [
                'label' => 'Avatar : ',
                'data_class' => null,
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
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
            'data_class' => User::class,
            'profile' => null,
            'cities' => null,

        ]);
    }
}
