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
//use JCLE\MemoBundle\Form\IconFileType;


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
        $max_result = 7;
        $tableau = array();
        $bla = array();
        
        if($user != 'anon.')
        {
            $recherche = $request->get('recherche');
            // Conversion de la recherche en tableau de mots
            $mots_cles = array_filter(explode(' ',$recherche));
            
            if($request->isXmlHttpRequest())
            {
                $em = $this->getDoctrine()->getManager();
                $notes = $em->getRepository('JCLEMemoBundle:Note')
                                ->recherche($mots_cles,$user);
                
                $tableau = $this->transformResults($notes, $mots_cles, $max_result);
                
                return new JsonResponse($tableau); 
            }
        }
    }
    
    /**
     * Créer un tableau servant à l'autocompletion principale
     * @param type $notes -> Les notes récupérées qui ont au moins un mot cle correspondant
     * @param type $mots_cles -> Tableau de mots cles
     * @param type $max_result -> Nbre Max a afficher
     * @return array -> Tableau [icon][value][pertinence]
     */
    public function transformResults($notes, $mots_cles, $max_result)
    {
        $tableau = array();
        
        foreach ($notes as $value)
        {
            $tableau[] = array(
                'icon' => $value->getIcon()->getId(),
                'value' => $value->getTitre(),
                'pertinence' => $this->calculPertinence($value->getDescription().' '.$value->getTitre().' '.$value->getIcon()->getAlt(), $mots_cles)
                );
        }
        usort($tableau, function ($a, $b) {
            return strnatcmp($a['pertinence'], $b['pertinence']);
        });
        // inverse l'ordre du tableau
        $tableau = array_reverse($tableau);
        // limite le nombre de résultats
        $tableau = array_slice($tableau,0,$max_result);
        
        return $tableau;
    }
    
    /**
     * Calcul le nombre de fois ou les mots cles sont retrouvés dans la chaine donnée
     * @param type $chaine_de_recherche -> La chaine à controler
     * @param type $tab_de_mots_cles -> Le tableau de mots cles a trouver
     * @return int -> nombre d'occurence
     */
    public function calculPertinence ($chaine_de_recherche, $tab_de_mots_cles)
    {
        $pertinence = 0;
        foreach ($tab_de_mots_cles as $value) {
            $pertinence += mb_substr_count($chaine_de_recherche, $value);
        }
        return $pertinence;
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
    
    // A MODIFIER ****************************************** CreatePagination **********************************************************************
    
    public function prerechercheAction(Request $request)
    {
        $value = $request->get('recherche');
        
//        dump($request);
//        
//        return new Response('<body>pause search</body>');
        
        if($value != "")
        {
            return $this->redirect($this->generateUrl('jclememo_recherche', array(
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
    public function rechercheAction($value,$page=1, $maxParPage=4, Request $request)
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
            $recherche = $depot->recherche($searches, $user);

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
        
        public function searchNotesByIconAction($iconAlt, $page=1, $maxParPage=4, Request $request)
        {
            $username = $this->getUser()->getUsername();
            
            $em = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('JCLEMemoBundle:Note');
            $note = $em->findByIcon($iconAlt,$username);
            
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
        
//        public function filarianeAction(Note $note)
//        {
//            return new Response($note->getIcon()->getAlt());
//        }
        
}
