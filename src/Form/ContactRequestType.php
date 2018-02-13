<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\ContactRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContactRequestType.
 */
class ContactRequestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Your name',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Foo Bar'],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Your email address',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'foo@bar.tld'],
                ]
            )
            ->add(
                'note',
                TextareaType::class,
                [
                    'label' => 'Your message',
                    'attr' => ['class' => 'form-control', 'placeholder' => '...'],
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ContactRequest::class,
        ));
    }
}
