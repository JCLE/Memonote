<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JCLE\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JCLE\MemoBundle\Entity\Icon;
use JCLE\UserBundle\Entity\User;

/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $this->createNoteIcon($user);

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Création d'une copie de l'icone par defaut pour l'utilisateur spécifié
     * @param type $user -> utilisateur
     */
    public function createNoteIcon(User $user)
    {
        $manager = $this->getDoctrine()->getManager();
        $icon= new Icon();
        $icon->setAlt('Temp');
        $icon->setCreateur($user);
        $manager->persist($icon);
        $manager->flush();
        $idFile = $icon->getId();
        $directory = __DIR__.'/../../../../www/';
        $filePath = $directory.'bundles/jclememo/images/note.png';
        $fileCopy = $directory.'uploads/icon/'.$idFile.'.png';
        copy($filePath,$fileCopy);
        $File = new File($fileCopy);
        $fileUploaded = new UploadedFile($File, 'note', null, null, null, true); // TODO : une fois en ligne delete les 3 null et true car specifie juste que le fichier est situé localement
        $icon->setfichier($fileUploaded);
        $icon->setCreateur($user);
        $icon->setAlt($idFile);
        $manager->persist($icon);
        $manager->flush();
    }
}
