<?php

declare(strict_types=1);

namespace App\Form\Request;

use App\Entity\User;
use App\Model\Request\RegistrationRequest;
use App\Validator\Password;
use App\Validator\UniqueEntityProperty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Password(),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new UniqueEntityProperty(['entityClass' => User::class, 'field' => 'email', 'repositoryMethod' => 'findByLower', 'lowercase' => true]),
                    new NotBlank(),
                    new Email(), //TODO more strict check
                ],
            ])
//            ->add('agreeTerms', CheckboxType::class, [
//                'mapped' => false,
//                'constraints' => [
//                    new IsTrue([
//                        'message' => 'You should agree to our terms.',
//                    ]),
//                ],
//            ])
//            ->add('recaptcha', TextType::class, [
//                'mapped' => false,
//                'constraints' => [
//                    new Recaptcha(),
//                ],
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegistrationRequest::class,
        ]);
    }
}
