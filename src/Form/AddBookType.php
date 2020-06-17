<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AddBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('NameBook', TextType::class, ['label' => 'Название книги'])
            ->add('AuthorBook', TextType::class, ['label' => 'Автор'])
            ->add('imageLocation', FileType::class, array('label' => 'Обложка'))
            ->add('bookLocation', FileType::class, array('label' => 'Файл книги'))
            ->add('ReadingDate', DateType::class, array('label' => 'Дата прочтения'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
