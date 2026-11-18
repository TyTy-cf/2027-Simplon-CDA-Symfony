<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stageName', null, [
                'label' => 'Nom de scène',
                'label_attr' => [
                    // Attributs HTML de la balise LABEL liée à l'INPUT
                    'class' => 'fw-bold',
                ],
                'attr' => [
                    // Attributs HTML de la balise INPUT
                    'placeholder' => 'Nom de scène',
                ],
            ])
            ->add('biography', null, [
                'label' => 'Biographie',
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Biographie de l\'artiste',
                ],
            ])
            ->add('country', null, [
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Pays',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'btn btn-success',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
