<?php

namespace App\Type;

use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;



class TaskFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class
            ])

            ->add('estimation', RangeType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 3600
                ]
            ])
            ->add('isCompleted', ChoiceType::class, [
                'choices' => [
                    'Any' => null,
                    'Yes' => true,
                    'No' => false
                ]
            ])
            ->add('submit', SubmitType::class);
    }
}