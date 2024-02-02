<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Title'
            ])
            ->add('overview', TextareaType::class, [
                'label' => 'Overview',
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' =>[
                    'Cancelled' => 'Cancelled',
                    'Ended' => 'Ended',
                    'Returning' => 'Returning'
                ],
                'multiple' => false
            ])
            ->add('vote', NumberType::class, [
                'label' => 'Vote',
                'scale' => 1
            ])
            ->add('popularity', NumberType::class, [
                'label' => 'Popularity',
                'scale' => 2
            ])
            ->add('genres', TextType::class, [
                'label' => 'Genre(s)'
            ])
            ->add('firstAirDate', DateType::class, [
                'label' => 'First air date',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('lastAirDate', DateType::class, [
                'label' => 'Last air date',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('backdrop'/*,FileType::class,[
                'label' => "Backdrop"
            ]*/)
            ->add('poster'/*, FileType::class, [
                'label' => "Poster"
            ]*/)
            ->add('tmdbId', IntegerType::class, [
                'label' => "TmdbId",
                'constraints' => [
                    new PositiveOrZero(['message' => 'TmdbId doit être supérieur ou égal à zéro.'])
                ]
            ])
            //->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
