<?php

namespace App\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsFormsType;
use App\DBAL\PublishedStatusEnum;
use App\Entity\Story;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Статус',
                'attr' => ['class' => 'form-control'],
                'choices' => PublishedStatusEnum::getValuesNames(),
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Головна картинка',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('translations', TranslationsFormsType::class, [
                'form_type' => StoryTranslationType::class,
                'form_options' => []
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Story::class,
        ]);
    }
}
