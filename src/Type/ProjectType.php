<?php

namespace App\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;

class ProjectType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options ): void 
    {
        $actionName = $options['isNew'] ? "Create" : "Update";
        $currentSlug  =  $options['slug'];

        $constraints = [];

        if ($currentSlug !== null) {
            $constraints[] = new EqualTo($currentSlug);
        }


        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('slug', TextType::class, [
                'disabled' => !$options['isNew'],
                'constraints' => $constraints
            ])
            ->add($actionName, SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'isNew' => true,
            'slug' => null
        ]);

        $resolver->setAllowedTypes('isNew', 'bool');
    }



}

