<?php


namespace App\Form;

use App\Entity\Pictures;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'imageFile',
                FileType::class,
                [
                    'label' => 'Ajouter une image',
                    //'mapped' => false,
                    'data_class' => null,
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'Ajouter un alt'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pictures::class
        ]);
    }

}
