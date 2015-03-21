<?php

namespace JCLE\MemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JCLE\MemoBundle\Entity\Icon;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Icons implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
//        // Récupération du chemin des images
//        $baseurl = 'C:\Users\Valgore\Documents\wamp\www\Symfony\web\bundles\jclememo\images\\';
//        
//        $logos = array('php','unity3d','blender','doctrine','git','twig','csharp');
//        
//        $user = $manager->getRepository('JCLEUserBundle:User')
//                ->findOneBy(array('username'  =>  'jeff'));
//        
//        foreach($logos as $i => $logo)
//        {
//            $File = new File($baseurl.$logos[$i].'.png');
//            $fichier = new UploadedFile($File, $logos[$i], null, null, null, true); // Les 4 derniers sont facultatifs
//            // Je les ai rajoutés pour mettre "true" à la fin car il permet de spécifier "fichiers situés localement", par default à false
//            $icon= new Icon();
//            $icon->setfichier($fichier);
//            $icon->setCreateur($user);
//            $manager->persist($icon);
//            $manager->flush();
//        }
  }
}