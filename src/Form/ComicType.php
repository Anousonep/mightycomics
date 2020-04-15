<?php

namespace App\Form;

use App\Entity\Comic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComicType extends ApplicationType
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

//    lorsque le form est crée, le builder est composé des champs de la bdd donnée, title, slug etc
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration("Titre du comic", "Renseignez le titre du comic")
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfiguration("Adresse", "Renseignez l'adresse du slug (automatique)", [
                    'required' => false
                ])
            )
            ->add(
                'coverImage',
                UrlType::class,
                $this->getConfiguration("Url de l'image", "Renseignez l'adresse de l'image")
            )
            ->add(
                'about',
                TextType::class,
                $this->getConfiguration("A propos", "Renseignez une petite description de l'article")
            )
            ->add(
                'content',
                TextareaType::class,
                $this->getConfiguration("Description détaillée", "Renseignez une description détaillée du comic")
            )
            ->add(
                'scenario',
                TextType::class,
                $this->getConfiguration("Scénario", "Renseignez le nom du scénariste'")
            )
            ->add(
                'draw',
                TextType::class,
                $this->getConfiguration("Dessin", "Renseignez le nom du dessinateur")
            )
            ->add(
                'editor',
                TextType::class,
                $this->getConfiguration("Editeur", "Renseignez le nom de l'éditeur'")
            )
            ->add(
                'quantity',
                IntegerType::class,
                $this->getConfiguration("Quantité", "Renseignez la quantité du comic")
            )
            ->add(
                'price',
                MoneyType::class,
                $this->getConfiguration("Prix du comic", "Renseignez le prix du comic")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comic::class,
        ]);
    }
}



