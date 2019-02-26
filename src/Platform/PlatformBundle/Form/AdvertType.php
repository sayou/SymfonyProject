<?php

namespace Platform\PlatformBundle\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Platform\PlatformBundle\Form\CkeditorType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Platform\PlatformBundle\Repository\CategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern = 'C%';
        $builder
            ->add('date', DateTimeType::class)
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            //->add('content', TextareaType::class)
            ->add('content', CkeditorType::class)
            //->add('published', CheckboxType::class, array('required' => false, 'data' => false))
            ->add('image', ImageType::class)
            // ->add('categories', CollectionType::class, array(
            //     'entry_type' => CategoryType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true
            // ))
            // ->add('categories', EntityType::class, array(
            //     'class'        => 'PlatformPlatformBundle:Category',
            //     'choice_label' => 'name',
            //     'multiple'     => true
            // ))
            ->add('categories', EntityType::class, array(
                'class'         => 'PlatformPlatformBundle:Category',
                'choice_label'  => 'name',
                'multiple'      => true,
                'query_builder' => function(CategoryRepository $repository) use($pattern) {
                  return $repository->getLikeQueryBuilder($pattern);
                }
            ))
            ->add('save', SubmitType::class);
                    
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,    // 1er argument : L'évènement qui nous intéresse : ici, PRE_SET_DATA
                function(FormEvent $event) { // 2e argument : La fonction à exécuter lorsque l'évènement est déclenché
                  // On récupère notre objet Advert sous-jacent
                  $advert = $event->getData();
          
                  // Cette condition est importante, on en reparle plus loin
                  if (null === $advert) {
                    return; // On sort de la fonction sans rien faire lorsque $advert vaut null
                  }
          
                  // Si l'annonce n'est pas publiée, ou si elle n'existe pas encore en base (id est null)
                  if (!$advert->getPublished() || null === $advert->getId()) {
                    // Alors on ajoute le champ publishe
                    
                    $event->getForm()->add('published', CheckboxType::class, array('required' => false));
                  } else {
                    // Sinon, on le supprime
                    $event->getForm()->remove('published');
                  }
                }
              );
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
