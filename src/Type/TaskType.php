<?php

namespace App\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType 
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
         ->add('name', TextType::class)
         ->add('description', TextareaType::class)
         ->add('dueDate', DateTimeType::class)
         ->add('Add', SubmitType::class);
         
    }

}