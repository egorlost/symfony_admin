<?php

namespace App\Form;

use App\Entity\StoryTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoryTranslationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Назва',
                'attr' => ['class' => 'form-control name_field'],
                'required' => false,
            ])
            ->add('slug', null, [
                'label' => 'Транслітерація *',
                'attr' => ['class' => 'form-control translit_field'],
                'required' => false,
            ])
            ->add('annotation', TextareaType::class, [
                'label' => 'Анотація',
                'attr' => ['class' => 'form-control ckeditorBasic'],
                'required' => false,
            ])
            ->add('storyBlockTextType', CollectionType::class, [
                'entry_type' => StoryBlockTextType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_delete' => true,
                'allow_add' => true,
                'delete_empty' => true,
                'error_bubbling' => false,
            ])
            ->add('storyBlockImageType', CollectionType::class, [
                'entry_type' => StoryBlockImageType::class,
                'entry_options' => ['label' => false],
                'allow_delete' => true,
                'allow_add' => true,
                'delete_empty' => true,
            ])
            ->add('seoTitle', null, [
                'label' => 'СЕО заголовок',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('seoDescription', TextareaType::class, [
                'label' => 'СЕО Опис',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StoryTranslation::class
        ]);
    }
}
