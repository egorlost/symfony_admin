<?php


namespace App\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsFormsType;
use App\DBAL\PublishedStatusEnum;
use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $image = $builder->getData()->getImage();

        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'Виберіть головне зображення',
                'attr' => ['class' => 'form-control',],
                'required' => $image === null,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Статус',
                'attr' => ['class' => 'form-control'],
                'choices' => PublishedStatusEnum::getValuesNames()
            ])
            ->add('publishDate', DateTimeType::class, [
                'label' => 'Дата публікації',
                'attr' => ['class' => 'form-control '],
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm'
            ])
            ->add('translations', TranslationsFormsType::class, [
                'form_type' => BlogTranslationType::class,
                'form_options' => []
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
            'translation_domain' => 'Admin',
        ]);
    }

}