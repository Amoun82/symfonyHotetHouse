<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_arrivee', DateType::class,[
                "widget" => "single_text",
                "label" => "Date d'arrivée",
                "required" => true
            ])
            ->add('date_depart', DateType::class,[
                "widget" => "single_text",
                "label" => "Date de départ",
                "required" => true
            ])
            ->add('prenom')
            ->add('nom')
            ->add('telephone')
            ->add('email');
            //->add('date_enregistrement')
        if(!$options['is_chambre'])
        {
            $builder
                ->add('id_chambre', EntityType::class, [
                    'class' => Chambre::class,
                    'choice_label' => 'TitrePrix',
                ]);
        }

        if($options['is_admin'])
        {
            $builder
                ->add('prix_total');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'is_admin' => false,
            'is_chambre' => false,
        ]);
    }
}
