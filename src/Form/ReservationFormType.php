<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('depAddress',)
            ->add('destination')
            ->add('reservation_datetime', null, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('nbPassengers', IntegerType::class, [
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 4,
                ],
               

            ])
           
            ->add('submit', SubmitType::class, [
                'label' => 'Réserver'
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
