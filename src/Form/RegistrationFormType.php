<?php

namespace App\Form;

use App\Config\GenderType;
use App\Entity\User;
use Doctrine\DBAL\Types\StringType;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4'],
                'constraints' => [
                    new NotBlank(options: ['message' => 'Please enter a valid email address']),
                    new Email([
                        'message' => 'The email "{{ value }}" is not a valid email address.',
                        'mode' => 'strict', // Optional: Use strict, loose (default), or html5 mode
                    ])
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4'],
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(options: ['message' => 'Please enter a password']),
                    new Length(options: [
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4'],
                'constraints' => [
                    new NotBlank(options: ['message' => 'Please enter your first name']),
                ],
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4'],
                'constraints' => [
                    new NotBlank(options: ['message' => 'Please enter your last name'])
                ],
            ])
            ->add('nickname', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4'],
            ])
            ->add('gender', EnumType::class, [
                'class' => GenderType::class,
                'attr' => ['class' => 'form-control'],
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
