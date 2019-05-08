<?php
declare(strict_types = 1);

namespace App\Form\Type\User;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'app.form.user.name'
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'attr' => [
                        'placeholder' => 'app.form.user.password'
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'app.form.user.password_repeat'
                    ],
                ],
            ]);
    }

    public function getBlockPrefix()
    {
        return 'app_user';
    }
}
