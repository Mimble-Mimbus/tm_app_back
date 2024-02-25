<?php

namespace App\Form;

use App\Entity\Rpg;
use App\Entity\RpgActivity;
use App\Entity\TriggerWarning;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TwType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('theme', ChoiceType::class, [
            'multiple' => false,
                'attr' => [
                    'class' => 'bg-light'
                ],
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'delimiter' => ',',
                    'hideSelected' => true,
                    'maxItems' => 1
                ],
            'autocomplete_url' => '/ajax/autocomplete/triggers',
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TriggerWarning::class,
        ]);
    }
}
