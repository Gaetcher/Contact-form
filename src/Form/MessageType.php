<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'app.form.firstname',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'app.form.lastname',
            ])
            ->add('email', EmailType::class, [
                'label' => 'app.form.email',
            ])
            ->add('subject', TextType::class, [
                'label' => 'app.form.subject',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'app.form.message',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'app.form.submit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
