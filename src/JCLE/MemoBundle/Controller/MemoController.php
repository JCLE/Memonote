<?php

namespace JCLE\MemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use JCLE\MemoBundle\Entity\Note;
use JCLE\MemoBundle\Entity\Icon;
//use JCLE\MemoBundle\Form\NoteType;
//use JCLE\MemoBundle\Form\IconType;
use JCLE\MemoBundle\Form\IconFileType;


class MemoController extends Controller
{
    /**
     * Voir page d'accueil
     * @return render
     */
    public function indexAction()
    {
        return $this->render('JCLEMemoBundle:Memo:index.html.twig');
    }
    
    /**
     * Voir carousel
     * @return render
     */
    public function carouselAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $icons = $em->getRepository('JCLEMemoBundle:Note')
                        ->findIconsHaveNotes($user);
        return $this->render('JCLEMemoBundle:Memo:carousel.html.twig',array(
            'icons'    =>  $icons
        ));
    }
    
    /**
     * Créer une nouvelle note
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return redirect or render
     */
    public function nouveauAction(Request $request)
    {
        // nouveau commit ?
//        $note = new Note();
//        $user = $this->getUser();
//        $em = $this->get('doctrine.orm.entity_manager');
//        $em = $this->getDoctrine()->getManager();
//        $securityContext = $this->get('security.context');
        
//        $form = $this->createForm(new NoteType( $securityContext, $em ),$note);
        $form = $this->createForm('jcle_memobundle_note');
        $form->handleRequest($request);
        $note = $form->getData();
        
        if($form->isValid())
        {
            $note->setCreateur($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info-succes', 'Enregistrement effectué');
            
            return $this->redirect($this->generateUrl('jclememo_voir', array(
                'slug' => $note->getSlug()
                )));
        }
        
        return $this->render('JCLEMemoBundle:Memo:nouveau.html.twig', array(
            'form'  =>  $form->createView()
        ));
    }
    
    /**
     * Modifier une note
     * @param \JCLE\MemoBundle\Entity\Note $note
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return redirect or render
     */
    public function modifierAction(Note $note, Request $request)
    {
//        dump($note);
//        $em = $this->getDoctrine()->getManager();
//        $securityContext = $this->get('security.context');
//        
//        $form = $this->createForm(new NoteType( $securityContext, $em ),$note);
//        $form->handleRequest($request);
        
        $form = $this->createForm('jcle_memobundle_note',$note);
        $form->handleRequest($request);
//        $note = $request->attributes->get('note');
//        dump($request->attributes->get('note'));
//        $note = $form->getData();
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info-warning', 'Modification enregistrée');
            
            return $this->redirect($this->generateUrl('jclememo_voir', array(
                'slug' => $note->getSlug()
                )));
        }
        
        return $this->render('JCLEMemoBundle:Memo:nouveau.html.twig', array(
            'form'  =>  $form->createView(),
            'note'  =>  $note
        ));
    }
    
    /**
     * Voir une note
     * @param \JCLE\MemoBundle\Entity\Note $note
     * @return render
     */
    public function voirAction(Note $note)
    {
        return $this->render('JCLEMemoBundle:Memo:voir.html.twig', array(
            'note'    =>  $note
        ));
    }
    
    
    /**
     * Supprimer une note
     * @param \JCLE\MemoBundle\Entity\Note $note
     * @return redirect
     */
    public function supprimerAction(Note $note)
    {
        $user = $this->getUser();
        
        if($user == $note->getCreateur())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($note);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info-danger', 'Note supprimée');
            return $this->redirect($this->generateUrl('jclememo_accueil'));
        }
        else
        {
            return new Response("Vous n'avez pas les droits necessaires.");
        }
    }
    
    /**
     * Créer un nouvel icone
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return redirect or render
     */
    public function ajoutIconAction(Request $request)
    {      
//        $form = $this->createForm('jcle_memobundle_note');
//        $form->handleRequest($request);
//        $note = $form->getData();
        
//        $icon = new Icon();
//        $form = $this->createForm(new IconFileType(),$icon);
        $form = $this->createForm('jcle_memobundle_iconfile');
        $form->handleRequest($request);
        $icon = $form->getData();
        
                if ($form->isValid()) 
                {                    
                    $user = $this->getUser();
                    $em = $this->getDoctrine()->getManager();
                    $icon->setCreateur($user);
                    $em->persist($icon);
                    $em->flush();
                    
                    $this->get('session')->getFlashBag()->add('info-succes', 'Icone ajoutée');
            
                    return $this->redirect($this->generateUrl('jclememo_voiricon',array('id' => $icon->getId())));

                    // On redirige vers la page de visualisation de l'article nouvellement créé
//                    return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
                }
        
        return $this->render('JCLEMemoBundle:Memo:ajoutIcon.html.twig', array(
            'form'  =>  $form->createView()
        ));
    }
    
    /**
     * Voir et modifier icone
     * @param \JCLE\MemoBundle\Entity\Icon $icon
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return redirect or render
     */
    public function voirIconAction(Icon $icon, Request $request)
    {            
//        $form = $this->createForm(new IconType(),$icon);
        $form = $this->createForm('jcle_memobundle_icon',$icon);
        $form->handleRequest($request);
            if ($form->isValid()) 
            {                    
                $em = $this->getDoctrine()->getManager();
                $em->persist($icon);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info-warning', 'Icone modifiée');

                return $this->redirect($this->generateUrl('jclememo_voiricon',array('id' => $icon->getId() )));

                // On redirige vers la page de visualisation de l'article nouvellement créé
//                    return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
            }
        
        return $this->render('JCLEMemoBundle:Memo:voirIcon.html.twig', array(
            'form'  =>  $form->createView()
                ,'id'   => $icon->getId()
        ));
    }
    
    /**
     * Supprimer une icone
     * @param \JCLE\MemoBundle\Entity\Icon $icon
     * @return redirect or render
     */
    public function supprIconAction (Icon $icon)
    {
        $user = $this->getUser();
        
        if($user == $icon->getCreateur())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($icon);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info-danger', 'Icone supprimée');
            return $this->redirect($this->generateUrl('jclememo_accueil'));
        }
        else
        {
            return new Response("Vous n'avez pas les droits necessaires.");
        }  
    }
    
    /**
     * Recherche ajax
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajaxSearchAction(Request $request)
    {
        $user = $this->getUser();
        
        if($user != 'anon.')
        {
            $search = $request->get('recherche');
            // Conversion de la recherche en tableau de mots
            $searches = array_filter(explode(' ',$search));

            if($request->isXmlHttpRequest())
            {
                $em = $this->getDoctrine()->getManager();
                $recherche = $em->getRepository('JCLEMemoBundle:Note')
                                ->recherche($searches,$user,1,10);

                foreach ($recherche as $value)
                {
                    $tableau[] = array(
                            'label' => $value->getTitre(),
                            'value' => $value->getTitre(),
                            'desc' => $value->getDescription(),
                            'icon' => $value->getIcon()->getId()
                        );
                }

                return new JsonResponse($tableau); 
            }
        }
    }
    
    
//    public function ajaxImageAction(Request $request)
//    {
//        $search = $request->get('image');
//        if($request->isXmlHttpRequest())
//        {
//            $em = $this->getDoctrine()->getManager();
//            $retour = $em->getRepository('JCLEMemoBundle:Icon')
//                        ->findOneBy(array('id' => $search));
//            
//            $icon = array(
//                'name'   =>  $retour->getId().'.'.$retour->getExtension(),
//                'alt'  =>  $retour->getAlt()
//            );
//            
//            return new JsonResponse($icon);
//        }
//    }
    
    // A MODIFIEr ****************************************** CreatePagination **********************************************************************
    
    public function searchAction(Request $request)
    {
        $value = $request->get('recherche');
        
        if($value != "")
        {
            return $this->redirect($this->generateUrl('jclememo_mysearch', array(
                'value' => $value
                    )));
        }
        else
        {
            $this->get('session')->getFlashBag()->add('info-warning', 'Il faut renseigner au moins un mot !');
            return $this->redirect($this->generateUrl('jclememo_accueil'));
        }
    }
    /**
     * Recherche lors de soumission formulaire
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mysearchAction($value,$page=1, $maxParPage=10, Request $request)
    {
        $user = $this->getUser();

        // Si pas de valeur transmise, récupération de la requete
//        if($request->getMethod()=='POST')
//        {
//            $value = $request->get('recherche');
//        }

        $em = $this->getDoctrine()->getManager();
        $depot = $em->getRepository('JCLEMemoBundle:Note');
        
        $note = $depot->findOneBy(array('titre'=>$value,'createur'=>$user));
        
        // Si un titre correspond à cette recherche, affichage de la note correspondante
        if($note)
        {                
            return $this->redirect($this->generateUrl('jclememo_voir', array(
                'slug' => $note->getSlug()
                    )));
        }
        else
        {
            // Détachement de la recherche mot à mot
            $searches = array_filter(explode(' ',$value));
            $recherche = $depot->recherche($searches,$user,$page,$maxParPage);

            foreach ($recherche as $key => $_value)
            {
                $note[$key] = $_value;
            }
            
            if($note)
            {
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $note,
                    $request->query->get('page', $page)/*page number*/,
                    $maxParPage/*limit per page*/
                );
            
//            $count_recherche = $depot->countRecherche($searches,$user,$page,$maxParPage); 
            
//            $route = 'jclememo_search';
//            $pagination = $this->createPagination($value,$page, $maxParPage, $count_recherche, $route);
            
            
                return $this->render('JCLEMemoBundle:Memo:voir.html.twig', array(
                        'note' => $note
                        ,'pagination' => $pagination
                        ,'value' => $value
                    ));
            }
            else
            {
                $this->get('session')->getFlashBag()->add('info-warning', 'aucune note correspondant à la recherche : '.$value);
                return $this->redirect($this->generateUrl('jclememo_accueil'));
            }
          }
            
        }
        
//        public function listAction(Note $note,Request $request)
//        {
//            $em    = $this->getDoctrine()->getManager();
//            $query = $em->getRepository('JCLEMemoBundle:Note')
//                        ->find($note->getId());
//
//            $paginator  = $this->get('knp_paginator');
//            $pagination = $paginator->paginate(
//                $query,
//                $request->query->get('page', 1)/*page number*/,
//                10/*limit per page*/
//            );
//
//            // parameters to template
//            return $this->render('JCLEMemoBundle:Memo:voir.html.twig', array(
//                'note'  =>  $note
//                ,'pagination' => $pagination
//                    ));
//        }

        
        /**
         * Créer un array de pagination
         * @param type $page
         * @param type $maxParPage
         * @param type $count
         * @param type $route
         * @return array
         */
//        public function createPagination(/*$value,*/$page, $maxParPage, $count, $route)
//        {
//            return $pagination = array(
////                    'value' => $value,
//                    'page' => $page,
//                    'max_par_page' => $maxParPage,
//                    'count_result' => $count,
//                    'route' => $route,
//                    'pages_count' => ceil($count / $maxParPage),
//                    'route_params' => array(/*'value'=>$value*/)
//                );
//        }
        
        public function searchNotesByIconAction($iconAlt, $page=1, $maxParPage=10, Request $request)
        {
            $username = $this->getUser()->getUsername();
            
            $em = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('JCLEMemoBundle:Note');
            
            $note = $em->findByIcon($iconAlt,$username, $page, $maxParPage);
            
//            $count_Icon = $em->countIcon($iconAlt,$username);            
//            $route = 'jclememo_searchnotesbyicon';
//            
//            $pagination = $this->createPagination($page, $maxParPage, $count_Icon, $route);
            
            if($note)
            {
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $note,
                    $request->query->get('page', $page)/*page number*/,
                    $maxParPage/*limit per page*/
                );
            
                return $this->render('JCLEMemoBundle:Memo:voir.html.twig', array(
                    'note' => $note   
                    ,'icon' => $iconAlt
                    ,'pagination' => $pagination
                    ));
            }
            else
            {
                $this->get('session')->getFlashBag()->add('info-warning', 'aucune note correspondant à la recherche : '.$iconAlt);
                return $this->redirect($this->generateUrl('jclememo_accueil'));
            }
        }
        
        public function filarianeAction(Note $note)
        {
            return new Response($note->getIcon()->getAlt());
        }
        
}
