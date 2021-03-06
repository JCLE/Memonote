<?php

namespace JCLE\MemoBundle\Entity;

use Doctrine\ORM\EntityRepository;
use JCLE\UserBundle\Entity\User;

/**
 * IconRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IconRepository extends EntityRepository
{
    /**
     * Retourne le nombre d'icones d'un utilisateur défini
     * @param User $user
     * @return int
     */
    public function getNbIcon(User $user)
    {
        $qb= $this->createQueryBuilder('i')
            ->select('COUNT(DISTINCT i.id)')
//                ->from('JCLEMemoBundle:Icon', 'i')
                ->where('i.createur = :user')
                ->setParameter(':user', $user);
        
        return $qb->getQuery()
                ->getSingleScalarResult();
    }
    
    /**
     * Rechercher toutes les icones d'un utilisateur spécifié, utilisé par le formulaire de creation de notes
     * @param type $user
     * @return array
     */
    public function findIconsFromUser(User $user)
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('DISTINCT i, n')
            ->leftJoin('i.notes','n')
            ->where('i.createur = :username')
            ->setParameter('username', $user );
        
        return $qb->getQuery()
                ->getArrayResult();
    }
}
