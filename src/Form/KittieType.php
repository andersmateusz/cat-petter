<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Kittie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\Dropzone\Form\DropzoneType;

class KittieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('breed')
            ->add(
                'catPicture',
                DropzoneType::class,
                [
                    'mapped' => false,
                    'constraints' => [
                        new Assert\Image(maxSize: 5000000),
                        new Assert\NotNull
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kittie::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
