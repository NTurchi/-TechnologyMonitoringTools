<?php

namespace AbreNTech\AbtBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use AbreNTech\AbtBundle\Entity\Link;
use AbreNTech\AbtBundle\Entity\Category;
use AbreNTech\AbtBundle\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom du lien : ',
                ))
            ->add('type', EntityType::class, array(
                'class' => 'AbreNTech\AbtBundle\Entity\Type',
                'choice_label' => 'name',
                'label' => 'Type du lien : '
            ))
            ->add('linkstr', TextType::class, array('label' => 'Lien : '))
            ->add('description', TextareaType::class, array('label' => 'Description : '))
            ->add('category', EntityType::class, array(
                'class' => 'AbreNTech\AbtBundle\Entity\Category',
                'choice_label' => 'name',
                'label' => 'Categorie : '
            ))
            ->add('add', SubmitType::class, array('label' => 'Ajouter/Modifier'));

        /*$builder
            ->add('type', EntityType::class, array(
                'class' => 'AbreNTech\AbtBundle\Entity\Type',
                'choice_label' => 'name',
                'label' => 'Type du lien : ',
            ))
            ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event)
            {
                $type = $event->getData();

                $form = $event->getForm();

                if (!$type){
                    return;
                } else {
                    if ($type->getName() == "XML"){
                        $form
                            ->add('linkstr', TextType::class, array('label' => 'Lien : '));
                    } else {
                        $form
                            ->add('name', TextType::class, array('label' => 'Nom du lien : '))
                            ->add('linkstr', TextType::class, array('label' => 'Lien : '))
                            ->add('description', TextareaType::class, array('label' => 'Description : '));
                    }
                    $form
                        ->add('category', EntityType::class, array(
                            'class' => 'AbreNTech\AbtBundle\Entity\Category',
                            'choice_label' => 'name',
                            'label' => 'Categorie : '
                        ))
                        ->add('add', SubmitType::class, array('label' => 'Ajouter/Modifier'));
                }
            });*/

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Link::class,
        ));
    }
}