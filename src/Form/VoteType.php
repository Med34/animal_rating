<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Vote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('personName', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('animalName', TextType::class, [
                'label' => 'Animal',
            ])
            ->add('score', IntegerType::class, [
                'label' => 'Score',
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Voter',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vote::class,
        ]);
    }
}
