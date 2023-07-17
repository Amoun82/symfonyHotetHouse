<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options);
        $builder
            ->add('pseudo')
            ->add('roles', ChoiceType::class, [
                "choices"   => [
                    "Admin" => "ROLE_ADMIN",
                    "Utilisateur" => "ROLE_USER"
                ],
                "multiple" => true,
                "expanded" => true,
                "label" => "Droit d'accès"
            ])
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('civilite', ChoiceType::class, [
                "choices"   => [
                    "Homme" => "h",
                    "Femme" => "f"
                ],
                "multiple" => false,
                "expanded" => true
            ]);
        if (!$options['is_edit']) {
            $builder->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'les mots de passes ne correspondent pas',
                'first_options' => [
                    "label" => "mot de passe",
                    'attr' => [
                        'placeholder' => 'votre mot de passe',
                    ]
                ],
                'second_options' => [
                    "label" => "Confirmation le mot de passe",
                    'attr' => [
                        'placeholder' => 'confimer votre mot de passe',
                    ]
                ],
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe est obligation',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre de mot de passe doit faire au minimum {{ limit }} charactères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%#*?&])[A-Za-z\d@$!%#*?&]{8,}$/',
                        'match' => true,
                        'message' => 'votre mot de passe doit contenir au moins un chiffre, un caractère spéciale, une lettre en majuscule et une minuscule !'
                    ]),
                ],
            ]);
        }
        //->add('date_enregistrement')

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
            'is_edit' => false
        ]);
    }
}
