<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\User;
use App\Entity\UserVote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserVoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('CreatedOn', DateTimeType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('ModifiedOn', DateTimeType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Smash', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-check-label p-1'],
                'row_attr' => ['class' => 'form-check mb-4', 'style' => 'padding-left: 1.5em;'],
            ])
            ->add('User', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Candidate', EntityType::class, [
                'class' => Candidate::class,
                'choice_label' => 'id',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserVote::class,
        ]);
    }
}
