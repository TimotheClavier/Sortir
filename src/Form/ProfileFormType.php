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
        $profile = new User();
        $profile = $options['choices'];
        $nb = $profile->getCity()->getId();

        $cities = [];
        foreach ($options ['cities'] as $city) {
            if (!empty($city->getLibelle())) {
                $cities[$city->getId()] = $city->getLibelle();
            }
        }
        //$options['cities'][$profile->getCity()->getId()]

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
                'invalid_message' => 'Erreur',
                'required' => false,
                'first_options' => array('attr' => ['class' => 'form-control col w-50'],'label'=>'Mot de passe :'),
                'second_options' => array('attr' => ['class' => 'form-control col w-50'], 'label'=>'Confirmation :'),
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control w-50'
                ]
            ])
            ->add('city', ChoiceType::class, [
                'choices'  => $options['cities'],
                'choice_label' => "libelle",
                "choice_value" => "id",
                "attr" => [
                    'class' =>"form-control w-50",
                    'value' => $cities[$profile->getCity()->getId()]
                ]
            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'choices' => null,
            'cities' => null,

        ]);
    }
}
