<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\Category;
use App\Config\GenderType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('ImgUrl', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
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
            ->add('Birthdate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Height', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Weight', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('HomeTown', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Married', ChoiceType::class, [
                'choices'  => [
                    'Yes' => true,
                    'No' => false,
                ],
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Income', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('PoliticalAffiliation', ChoiceType::class, [
                'choices' => [
                    'Unknown' => 'Unknown',
                    'Republican' => 'Republican',
                    'Democrat' => 'Democrat',
                    'Independant' => 'Independant',
                ],
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Bio', TextareaType::class, [
                'attr' => ['class' => 'form-control text-editor', 'id' => 'bio-editor'],
                'required' => false,
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Interests', TextareaType::class, [
                'attr' => ['class' => 'form-control text-editor', 'id' => 'interests-editor'],
                'required' => false,
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Lifestyle', TextareaType::class, [
                'attr' => ['class' => 'form-control text-editor', 'id' => 'lifestyle-editor'],
                'required' => false,
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('AdditionalInformation', TextareaType::class, [
                'attr' => ['class' => 'form-control text-editor', 'id' => 'additional-info-editor'],
                'required' => false,
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4']
            ])
            ->add('Categories', EntityType::class, [
                'class' => Category::class,
                'required' => false,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control lg-select'],
                'label_attr' => ['class' => 'form-label p-1'],
                'row_attr' => ['class' => 'form-floating mb-4'],
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}
