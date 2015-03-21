<?php

namespace JCLE\MemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JCLE\MemoBundle\Entity\Note;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JCLE\MemoBundle\Entity\Icon;
use JCLE\UserBundle\Entity\User;

class Notes implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
      
    // Les noms d'utilisateurs à créer
    $titres = array(
        'prob installation composer'
        ,'ligne de commande symfony'
        ,'installer composer'
        ,'mettre à jour composer'
    );
    $description = array(
        'dans le repertoir wamp/bin/php/php il y a un fichier php.ini'
        ,'shift + click droit sur le dossier symfony -> ouvrir fenetre commande.'
        ,'ligne de commande -> php -r "eval(\'?>\'.file_get_contents'
        ,'dans la ligne de commande de symfony -> php composer.phar self-update'
    );
    
        $user = new User;

        // Le nom d'utilisateur et le mot de passe sont identiques
        $user->setUsername('jeff');
        $user->setPlainPassword('jeff');
        $user->setEmail('jeff@rdy.fr');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));
        $manager->persist($user);
        $manager->flush();
    
//        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
//        $baseurl = $baseurl.'/web/bundles/jclememo/images/';
        $baseurl = '/Users/DragonBleu/Documents/MAMP/Symfony/www/bundles/jclememo/images/';
        $File = new File($baseurl.'symfony2.png');
        $fichier = new UploadedFile($File, 'symfony2', null, null, null, true); // Les 4 derniers sont facultatifs
        // Je les ai rajoutés pour mettre "true" à la fin car il permet de spécifier "fichiers situés localement", par default à false
        $icon= new Icon();
        $icon->setfichier($fichier);
        $icon->setCreateur($user);
        $manager->persist($icon);
        $manager->flush();    
      
//      $user= new User;
//      $user->setUsername('admin');
//      $user->setPlainPassword('admin');
//      $user->setEmail('administr@t.eur');
//      $user->setEnabled(true);
//      $user->setRoles(array('ROLE_ADMIN'));
//      $manager->persist($user);
//      $manager->flush();

    foreach ($titres as $i => $titre) {

      $titres[$i] = new Note;
      $titres[$i]->setTitre($titre);
      $titres[$i]->setDescription($description[$i]);
      $titres[$i]->setCreateur($user);
//      $titres[$i]->setCreateur($titre);
      $titres[$i]->setIcon($icon);

      $manager->persist($titres[$i]);
    }
    $manager->flush();
    
    
    // icons

        
        $logos = array('php','unity3d','blender','doctrine','git','twig','csharp');

        foreach($logos as $i => $logo)
        {
            $File = new File($baseurl.$logos[$i].'.png');
            $fichier = new UploadedFile($File, $logos[$i], null, null, null, true); // Les 4 derniers sont facultatifs
            // Je les ai rajoutés pour mettre "true" à la fin car il permet de spécifier "fichiers situés localement", par default à false
            $icon= new Icon();
            $icon->setfichier($fichier);
            $icon->setCreateur($user);
            $manager->persist($icon);
            $manager->flush();
        }
  }
}