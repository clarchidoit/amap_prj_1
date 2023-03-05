<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'E-mail'])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nom'])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Prénom'])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Adresse'])
            ->add('code_postal', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Code postal'])
            ->add('commune', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Commune'])
            ->add('tel_portable', TelType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Tel portable'])
            ->add('tel_2', TelType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Tel n°2'])
            ->add('RGPDConsent', CheckboxType::class, [
                'attr' => ['class' => 'ms-1'],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label' => 'Ces données personnelles ne seront utilisées qu\' à des fins internes. J\'accepte leur enregistrement'
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                            'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]   // le label est inutile car celui défini dans register.html.twig est prioritaire sur celui qui serait mis ici
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
