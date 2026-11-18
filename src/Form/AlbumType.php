<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('releasedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('poster', FileType::class, [
                'label' => 'Poster (jpg file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File(
                        extensions: ['jpg'],
                        extensionsMessage: 'PLease upload a valid jpg file'
                    ),
                ],
            ])
            ->add('artist', EntityType::class, [
                'label' => 'Artiste',
                'class' => Artist::class,
                'choice_label' => 'stageName',
                'query_builder' => function (ArtistRepository $artistRepository) {
                    return $artistRepository->createQueryBuilder('a')
                        ->orderBy('a.stageName', 'ASC');
                },
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'EP' => 'EP',
                    'album' => 'album',
                    'single' => 'single',
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
            'data_class' => Album::class,
        ]);
    }
}
