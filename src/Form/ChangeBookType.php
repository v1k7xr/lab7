<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ChangeBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('NameBook', TextType::class, ['label' => 'Название книги'])
            ->add('AuthorBook', TextType::class, ['label' => 'Название книги'])
            ->add('imageLocation', TextType::class, array('label' => 'Обложка','empty_data' => '',
            'required' => false,))
            ->add('bookLocation', TextType::class, array('label' => 'Файл книги','empty_data' => '',
            'required' => false,))
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
