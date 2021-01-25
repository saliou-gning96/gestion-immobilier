<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\TypeBien;
use App\Entity\Proprietaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('adresse')
            ->add('centre_fiscal')
            ->add('proprietaire', EntityType::class, [
                // looks for choices from this entity
                'class' => Proprietaire::class,
                'choice_label' => 'user.prenom'
            ])
            ->add('type_bien', EntityType::class, [
                // looks for choices from this entity
                'class' => TypeBien::class,
                'choice_label' => 'libelle'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }
}
