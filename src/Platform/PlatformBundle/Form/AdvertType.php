<?php

namespace Platform\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class)
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('content', TextareaType::class)
            ->add('published', CheckboxType::class, array('required' => false, 'data' => false))
            ->add('image', ImageType::class)
            // ->add('categories', CollectionType::class, array(
            //     'entry_type' => CategoryType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true
            // ))
            ->add('categories', EntityType::class, array(
                'class'        => 'PlatformPlatformBundle:Category',
                'choice_label' => 'name',
                'multiple'     => true
            ))
            ->add('save', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Platform\PlatformBundle\Entity\Advert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'platform_platformbundle_advert';
    }


}
