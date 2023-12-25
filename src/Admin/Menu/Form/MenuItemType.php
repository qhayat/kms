<?php

namespace Kms\Admin\Menu\Form;

use Kms\Admin\Menu\Entity\MenuItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Label',
                'attr' => [
                    'placeholder' => 'Label',
                    'class' => 'form-control',
                ],
            ])
            ->add('url', TextType::class, [
                'label' => 'Url',
                'attr' => [
                    'placeholder' => 'Url',
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => MenuItem::class,
        ]);
    }
}
