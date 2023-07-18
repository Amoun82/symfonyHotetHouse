<?php

namespace App\Form;

use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description_courte')
            ->add('description_longue')
            ->add('photo', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new File([
                        "maxSize" => '5120K',
                        "mimeTypes" => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        "mimeTypesMessage" => "Insérer un fichier valide",
                    ])
                ]
            ])
            ->add('prix_journalier')
            //->add('date_enregistrement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
