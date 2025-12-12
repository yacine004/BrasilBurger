<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CommandeFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clientNom', TextType::class, [
                'label' => 'Nom du Client',
                'required' => false,
                'attr' => ['placeholder' => 'Nom du client...']
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'État',
                'required' => false,
                'choices' => [
                    'En attente' => 'EN_ATTENTE',
                    'En cours' => 'EN_COURS',
                    'Validée' => 'VALIDEE',
                    'Annulée' => 'ANNULEE',
                ],
                'placeholder' => '-- Tous les états --'
            ])
            ->add('typeCommande', ChoiceType::class, [
                'label' => 'Type',
                'required' => false,
                'choices' => [
                    'Sur place' => 'PLACE',
                    'Retrait' => 'RETRAIT',
                    'Livraison' => 'LIVRAISON',
                ],
                'placeholder' => '-- Tous les types --'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
