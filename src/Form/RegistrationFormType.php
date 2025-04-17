<?php

namespace App\Form;

use App\Entity\User;
use Dom\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name' , TextType::class, ['attr'=> ['class' => 'form-control'], 'label' => 'Nom utilisateur :'])
            ->add('email', EmailType::class, ['attr'=> ['class' => 'form-control'], 'label' => 'Adresse mail :'])
            ->add('adresse', TextType::class, ['attr'=> ['class' => 'form-control'], 'label' => 'Adresse de livraison :'])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'  ], 'label' => 'Mot de passe :' ,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]) ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'  ], 'label' => 'Confirmer le mot de passe :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Confirmer le mot de passe :',
                    ]),
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
