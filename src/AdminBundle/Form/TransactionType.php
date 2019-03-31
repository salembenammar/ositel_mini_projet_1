<?php

namespace AdminBundle\Form;


use AdminBundle\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    const FORM_TYPE = 'transaction_type';
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @throws InvalidArgumentException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('amount', NumberType::class)
            ->add('isInput', CheckboxType::class, array(
                'required' => false
            ))
            ->add('isValid', CheckboxType::class, array(
                'required' => false
            ))

            ->add('category' , EntityType::class , array(
                'class' => 'AdminBundle:Category',
                'choice_label'  => function ($category) {
                    return sprintf('%s', $category->getTitle());
                },
                'mapped' => true,
                'required' => false
            ))
            ->add('tag' , EntityType::class , array(
                'class' => 'AdminBundle:Tag',
                'choice_label'  => function ($tag) {
                    return sprintf('%s', $tag->getName());
                },
                'mapped' => true,
                'multiple' => true,
                'required' => false
            ))
        ;

    }


    /**
     * @param OptionsResolver $resolver
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Transaction::class
        ));
    }

    public function getBlockPrefix()
    {
        return self::FORM_TYPE;
    }

}