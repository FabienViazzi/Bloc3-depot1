<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom :',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre prénom.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Le prénom doit comporter au moins {{ limit }} caractères.',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom :',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre nom.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères.',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail :',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre adresse e-mail.',
                    ]),
                    new Assert\Email([
                        'message' => 'L\'adresse e-mail "{{ value }}" n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options'  => [
                    'label' => 'Mot de passe :',
                    'attr' => ['placeholder' => '••••••••'],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe :',
                    'attr' => ['placeholder' => '••••••••'],
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir un mot de passe.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                        
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
