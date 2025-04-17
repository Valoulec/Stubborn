<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class OubliPassFromType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr'=> ['class' => 'form-control'],
                'label' => 'Email',
                'constraints' => [new Email(), new NotBlank()],
            ])
            ->add('oldPassword', PasswordType::class, [
                'attr'=> ['class' => 'form-control'],
                'label' => 'Ancien mot de passe',
                'constraints' => [new NotBlank()],
            ])
            ->add('newPassword', RepeatedType::class, [
                'attr'=> ['class' => 'form-control'],
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Nouveau mot de passe','attr'=> ['class' => 'form-control']],
                'second_options' => ['label' => 'Confirmer le nouveau mot de passe','attr'=> ['class' => 'form-control']],
                'constraints' => [new NotBlank()],
            ]);
    }
}









