<?php

namespace JCLE\MemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IconFileType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fichier','file')
                // Ne pas oublier l'enctype du form lors de FILE : <form method="post" {{ form_enctype(form) }}>
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JCLE\MemoBundle\Entity\Icon'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jcle_memobundle_iconfile';
    }
}
