<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\DeliveryLocations;
use DateTime;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('lastName', TextType::class, [
            'label' => 'Nom de famille',
            'required' => false,
        ])
        
        ->add('firstName', TextType::class, [
            'label' => 'prénom',
            'required' => false,
        ])
        ->add('adress', TextType::class, [
            'label' => 'Adress',
            'required' => false,
        ])
        ->add('adressSup', TextType::class, [
            'label' => 'Complément d\'adresse',
            'required' => false,
        ])
        ->add('city', TextType::class, [
            'label' => 'Ville',
            'required' => false,
        ])
        ->add('cp', TextType::class, [ // "01000"
            'label' => 'Code postal',
            'required' => false,
        ])
        ->add('country', ChoiceType::class, [
            'choices' => [
                'France' => 'France',
                'Belgique' => 'Belgique'
            ]
        ])
        ->add('phone', TextType::class, [ 
            'label' => 'Numéro de téléphone',
            'required' => false,
        ])
        ->add('email', TextType::class, [
            'label' => 'Email',
            'required' => false,
        ])
        // ->add('createdAt', DateTimeType::class, [
        //     'label' => 'Date de création',
        //     'required' => false,
        // ])
        // ->add('updatedAt', DateTimeType::class, [
        //     'label' => 'Date de mise à jour',
        //     'required' => false,
        // ])
        // ->add('deletedAt', DateTimeType::class, [
        //     'label' => 'Date de suppression',
        //     'required' => false,
        // ])
        ->add('deliveryLocations', DeliveryLocationsType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
