<?php

namespace App\Form;

use App\Entity\User;
use App\Config\GenderType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FirstName', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('LastName', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Email', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Change Password (optional)',
                'hash_property_path' => 'password',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('NickName', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Gender', EnumType::class, [
                'class' => GenderType::class,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4'],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => ['ROLE_ADMIN' => 'ROLE_ADMIN', 'ROLE_USER' => 'ROLE_USER'],
                'multiple' => true,
                'attr' => ['class' => 'form-control lg-select'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
