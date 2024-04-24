<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom de la prestation',
                ],
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Decription',
                'attr' => [
                    'placeholder' => 'Description de l\'article'
                ],
                'required' => true
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'placeholder' => 'Contenu de la prestation'
                ],
                'required' => true
            ])
            ->add('picture', FileType::class, [
                'label' => 'Illustration'
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
                'attr' => [
                    'placeholder' => 'Prix de la prestation'
                ],
                'required' => true
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée',
                'attr' => [
                    'placeholder' => 'Durée en minutes de la prestation'
                ],
                'required' => true
            ])
            ->add('Publier', SubmitType::class, [
                'label' => 'Publier l\'article'
                /*
                'attr' => [
                    'class' => 'btn btn-dark m-2'
                ]
                */
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
