<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{

//    /**
//     * permet d'avoir la config de base d'un champ du formulaire
//     * @param $label
//     * @param $placeholder
//     * @param array $options
//     * @return array
//     */
//    private function getConfiguration($label, $placeholder, $options = [])
//    {
//        return array_merge([
//
//            'label' => $label,
//            'attr' => [
//                'placeholder' => $placeholder
//            ]
//        ], $options);
//    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstname', TextType::class, $this->getConfiguration("Prénom", "Veuillez renseigner votre prénom...")
            )
            ->add(
                'lastname', TextType::class, $this->getConfiguration("Nom", "Veuillez renseigner votre nom...")
            )
            ->add(
                'email', EmailType::class, $this->getConfiguration("Email", "Veuillez renseigner votre email...")
            )
            ->add(
                'picture', UrlType::class, $this->getConfiguration("Photo de profil", "Lien de votre photo de profil...")
            )
            ->add(
                'hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Choisissez un mot de passe...")
            )
            ->add(
                'passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation du mot de passe", "Veuillez confirmer votre mot de passe")
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
