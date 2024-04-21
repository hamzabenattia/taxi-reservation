<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Driver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class DriverRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
            ], ['attr' => ['class' => 'form-control']])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
            ], ['attr' => ['class' => 'form-control']])
            ->add('address', TextType::class, [
                'label' => 'Addresse postal',
            ], ['attr' => ['class' => 'form-control']])
            ->add('companyName', TextType::class, [
                'label' => 'Le nom de la société',
            ], ['attr' => ['class' => 'form-control']])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone',
            ], ['attr' => ['class' => 'form-control']])
            

            ->add('email',EmailType::class)

          
            ->add('plainPassword', PasswordType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Mot de passe',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les termes',
'constraints' => [
    new IsTrue([
        'message' => 'You should agree to our terms.',
    ]),
],
])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un compte de chauffeur',
                'attr' => ['class' => 'w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Driver::class,
        ]);
    }
}
