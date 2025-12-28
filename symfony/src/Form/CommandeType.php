<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => function(Client $client) {
                    return $client->getPrenom() . ' ' . $client->getNom();
                },
                'label' => 'Client',
                'required' => true,
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'État',
                'choices' => [
                    'En attente' => 'En attente',
                    'En cours' => 'En cours',
                    'Validée' => 'Validée',
                    'Terminer' => 'Terminer',
                    'Annulée' => 'Annulée',
                ],
                'required' => true,
            ])
            ->add('mode', ChoiceType::class, [
                'label' => 'Mode de livraison',
                'choices' => [
                    'À emporter' => 'À emporter',
                    'Livraison à domicile' => 'Livraison à domicile',
                    'Sur place' => 'Sur place',
                ],
                'required' => false,
            ])
            ->add('montant', NumberType::class, [
                'label' => 'Montant (FCFA)',
                'required' => false,
                'html5' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
