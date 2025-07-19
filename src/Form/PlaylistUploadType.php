<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\PlaylistType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaylistUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => PlaylistType::getTypes(),
            ])
            ->add('file', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
