<?php


namespace App\Form;

use App\Entity\Category;
use App\Entity\Pictures;
use App\Entity\Steps;
use App\Entity\Tricks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nom'
                ]
            )
            ->add(
                'imageFile',
                FileType::class,
                [
                    'label' => 'Image principal'
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Déscription'
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'label' => 'Catégorie'
                ]
            )
            ->add(
                'picture',
                CollectionType::class,
                [
                    'entry_type' => PictureType::class,
                    'label' => false,
                    'entry_options' =>
                        [
                        'label' => 'Ajouter une image'
                        ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false
                ]
            )
            ->add(
                'videos',
                CollectionType::class,
                [
                    'entry_type' => VideoType::class,
                    'label' => false,
                    'entry_options' =>
                        [
                            'label' => 'Ajouter une video'
                        ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class
        ]);
    }

}
