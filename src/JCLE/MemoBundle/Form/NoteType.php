<?php

namespace JCLE\MemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Common\Persistence\ObjectManager;

class NoteType extends AbstractType
{
    private $securityContext;
    private $em;
    private $user;
    private $titreActuel;

    public function __construct(SecurityContext $securityContext, ObjectManager $em)
    {
        $this->securityContext = $securityContext;
        $this->user = $securityContext->getToken()->getUser();
        $this->em = $em;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     

        $arrayIcons = $this->em->getRepository("JCLEMemoBundle:Note")
                                ->findIconsFromUser($this->user);
//        dump($options);
//        $builder->getAttributes()
        if(!empty($options['data']))
        {
            $this->titreActuel = $options['data']->getTitre();
        }
        
        $builder
            ->add('titre', 'text')
            ->add('description', 'textarea');
//            ->add('date', 'date') // attention, si je mets datetime, j'ai les heures et minutes qui s'affichent également
            // Creation de combobox ou radio ou select
                // multiple : plusieurs choix selectionnables
                // expanded : choix radio ou boite de selection
                // voir image sur les formulaires
        
        if(null != $arrayIcons)
        {
        
            $builder->add('icon', 'entity', array( 
                'class'    =>  'JCLEMemoBundle:Icon'
                ,'property' =>  'alt'
                ,'multiple' =>  false
                ,'expanded' =>  false
                ,'query_builder' => function(EntityRepository $er) {
                        if (!$this->user) {
                            throw new \LogicException(
                                'Le NoteType ne peut pas être utilisé sans utilisateur connecté!'
                            );
                        }
                    return $er->createQueryBuilder('i')
                            ->where('i.createur = :username')
                            ->setParameter('username', $this->user )
                            ->orderBy('i.alt', 'ASC');
                    }
            
            ));
        }
            
            $builder->add('tag', 'text', array('required' => false))
                    ->add('notes', 'entity',array(
                        'class' =>  'JCLEMemoBundle:Note'
                        ,'property' =>  'titre'
                        ,'multiple' =>  true
                        ,'expanded' =>  false
                        ,'required' =>  false
                        ,'group_by' => 'icon.alt'
                        ,'query_builder' => function(EntityRepository $er) {
                        if (!$this->user) {
                            throw new \LogicException(
                                'Le NoteType ne peut pas être utilisé sans utilisateur connecté!'
                            );
                        }
                        // A refactoriser mais impossible de couper le $er pour inclure une condition entre ( a voir  :/ )
                        if(null !== $this->titreActuel)
                        {
                            return $er->createQueryBuilder('n')
                                ->where('n.createur = :username')
                                ->setParameter('username', $this->user )
                                ->andWhere('n.titre != :titreactuel')
                                ->setParameter('titreactuel', $this->titreActuel)
                                ->orderBy('n.titre', 'ASC');        
                        }
                        else
                        {
                            return $er->createQueryBuilder('n')
                                ->where('n.createur = :username')
                                ->setParameter('username', $this->user )
                                ->orderBy('n.titre', 'ASC');        
                        }
                            
                    }
                    ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JCLE\MemoBundle\Entity\Note'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jcle_memobundle_note';
    }
}
