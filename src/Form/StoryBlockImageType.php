<?php

namespace App\Form;

use App\Entity\StoryBlockImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoryBlockImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($builder->getData() && $builder->getData()->getImage()) {
            $image = ['image' => $builder->getData()->getImage()];
        }

        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'Картинка',
                'attr' => array_merge([
                    'class' => 'form-control ',
                ], $image ?? []),
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Опис',
                'attr' => ['class' => 'form-control ckeditorBasic'],
                'required' => false,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StoryBlockImage::class,
        ]);
    }
}
