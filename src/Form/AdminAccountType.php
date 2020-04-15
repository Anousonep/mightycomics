<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminAccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstname', TextType::class, $this->getConfiguration("Prénom", "Modifier le prénom de l'utilisateur...")
            )
            ->add(
                'lastname', TextType::class, $this->getConfiguration("Nom", "Modifier le nom de l'utilisateur...")
            )
            ->add(
                'email', EmailType::class, $this->getConfiguration("Email", "Modifier l'email de l'utilisateur...")
            )
            ->add(
                'picture', UrlType::class, $this->getConfiguration("Photo de profil", "Modifier la photo de profil de l'utilisateur...")
            )
            ->add(
                'userRoles', TextType::class, $this->getConfiguration("Role de cet utilisateur", "Modifier le rôle de l'utilisateur...")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}