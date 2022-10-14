<?php

namespace App\Form\Type;

use App\Entity\Author as AuthorEntity;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Author extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName')
            ->add('firstName', null, ['documentation' => [
                'type' => 'string',
                'description' => 'Your username.',
            ],])
            ->add('books', EntityType::class, [
                'documentation' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'class' => Book::class,
                'multiple' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => AuthorEntity::class]);
    }
}
