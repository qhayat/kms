<?php

namespace Kms\Admin\Setting\Form;

use Kms\Core\Content\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site_logo', FileType::class, [
                'label' => 'Logo du site',
                'attr' => [
                    'placeholder' => 'Logo du site',
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('site_name', TextType::class, [
                'label' => 'Nom du site',
                'attr' => [
                    'placeholder' => 'Nom du site',
                    'class' => 'form-control',
                ],
            ])
            ->add('site_description', TextareaType::class, [
                'label' => 'Description du site',
                'attr' => [
                    'placeholder' => 'Description du site',
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('blog_page', EntityType::class, [
                'label' => 'Page du blog',
                'class' => Page::class,
                'choice_label' => 'title',
                'attr' => [
                    'placeholder' => 'Page du blog',
                    'class' => 'form-control',
                ],
            ])
            ->add('home_page', EntityType::class, [
                'label' => 'Page d\'accueil',
                'class' => Page::class,
                'choice_label' => 'title',
                'attr' => [
                    'placeholder' => 'Page d\'accueil',
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }
}
