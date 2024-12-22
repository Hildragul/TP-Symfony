<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Adresse email',
            'label_attr' => [
                'class' => 'form-label fw-bold text-primary',
            ],
            'attr' => [
                'class' => 'form-control shadow-sm',
                'placeholder' => 'Entrez votre adresse email',
                'style' => 'background-color: #f8f9fa;',
                'aria-describedby' => 'emailHelp',
            ],
            'help' => 'Nous ne partagerons jamais votre email.',
            'help_attr' => [
                'class' => 'form-text text-muted',
            ],
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'label' => 'Accepter les termes et conditions',
            'mapped' => false,
            'label_attr' => [
                'class' => 'form-check-label fw-light text-secondary',
            ],
            'constraints' => [
                new IsTrue([
                    'message' => 'Vous devez accepter les termes et conditions.',
                ]),
            ],
            'attr' => [
                'class' => 'form-check-input',
            ],
        ])
        ->add('plainPassword', PasswordType::class, [
            'label' => 'Mot de passe',
            'label_attr' => [
                'class' => 'form-label fw-bold text-primary',
            ],
            'mapped' => false,
            'attr' => [
                'class' => 'form-control shadow-sm',
                'placeholder' => 'Créez un mot de passe',
                'autocomplete' => 'new-password',
                'style' => 'background-color: #f8f9fa;',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                    'max' => 4096,
                ]),
            ],
            'help' => 'Utilisez au moins 6 caractères.',
            'help_attr' => [
                'class' => 'form-text text-muted',
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
