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

class UpdateTrickPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'picture',
                CollectionType::class,
                [
                    'entry_type' => PictureType::class,
                    'entry_options' =>
                        [
                            'label' => 'Ajouter une image'
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
            'data_class' => Tricks::class,
            Pictures::class
        ]);
    }

}
