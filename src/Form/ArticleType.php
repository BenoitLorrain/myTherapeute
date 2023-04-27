<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre de l\'article'
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
            ->add('picture', FileType::class, [
                'label' => 'Image'
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Texte',
                'attr' => [
                    'placeholder' => 'Texte de l\'article'
                ],
                'required' => true
            ])
            ->add('Publier', SubmitType::class, [
                'label' => 'Publier l\'article',
                'attr' => [
                    'class' => 'btn btn-dark m-2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
