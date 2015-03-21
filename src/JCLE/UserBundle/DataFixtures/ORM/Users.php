<?php

namespace JCLE\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JCLE\UserBundle\Entity\User;

class Users implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
     //Les noms d'utilisateurs à créer
    $noms = array('vanessa', 'Hemingway', 'user');
    $email = array('test@test.test','azerty@azer.ty','ger@rd.menvu');

    foreach ($noms as $i => $nom) {
      // On crée l'utilisateur
      $users[$i] = new User;

      // Le nom d'utilisateur et le mot de passe sont identiques
      $users[$i]->setUsername($nom);
      $users[$i]->setPlainPassword($nom);
      $users[$i]->setEmail($email[$i]);
      $users[$i]->setEnabled(true);
      $users[$i]->setRoles(array('ROLE_ADMIN'));
      
      // Le sel et les rôles sont vides pour l'instant
//      $users[$i]->setSalt('');
//      $users[$i]->setRoles(array());

      // On le persiste
      $manager->persist($users[$i]);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}