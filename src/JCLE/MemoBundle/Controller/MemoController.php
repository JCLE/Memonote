<?php

namespace JCLE\MemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JCLE\MemoBundle\JCLEMemoConst;

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
    
    public function voirNotesAction(Request $request)
    {
        $user = $this->getUser();
        $icons = $this->getDoctrine()
                ->getManager()
                ->getRepository('JCLEMemoBundle:Icon')
                ->findIconsFromUser($user);
//                ->findNotesFromUser($user);
        $pagination = $this->createPagination($icons,$request, JCLEMemoConst::Max_Result_Notes_Mini);
        
        return $this->render('JCLEMemoBundle:Memo:voirNotes.html.twig', array(
            'icons'    =>  $icons
            ,'pagination'    =>  $pagination
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
            $nbIcon = $em->getRepository('JCLEMemoBundle:Icon')->getNbIcon($user);
            if($nbIcon > 1)
            {
                $em->remove($icon);
                $em->flush();
            }
            else
            {
                throw new \Exception("Vous ne pouvez pas supprimer la dernière icône qu'il vous reste.\nVous devez en posséder au moins une.");
            }
            $this->get('session')->getFlashBag()->add('info-danger', 'Icone supprimée');
            return $this->redirect($this->generateUrl('jclememo_accueil'));
        }
        else
        {
            throw new \Exception("Vous n'avez pas les droits nécessaires.");
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
        $tableau = array();
        
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
                
                $tableau = $this->transformResults($notes, $mots_cles, JCLEMemoConst::Max_Result_Ajax);
                
                return new JsonResponse($tableau); 
            }
        }
        else
        {
            throw new \Exception("Vous n'avez pas les droits nécessaires.");
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
    public function rechercheAction($value,$page=1, Request $request)
    {
        $user = $this->getUser();

        // Si pas de valeur transmise, récupération de la requete
//        if($request->getMethod()=='POST')
//        {
//            $value = $request->get('recherche');
//        }

        $em = $this->getDoctrine()->getManager();
        $depot = $em->getRepository('JCLEMemoBundle:Note');
        
        // Ici, je récupère les titres correspondant exactement à la recherche
        // ( car il est possible que 2 titres aient le même nom )
        $note = $depot->findBy(array('titre'=>$value,'createur'=>$user));
        $countNote = count($note);
//        $note = $depot->findOneBy(array('titre'=>$value,'createur'=>$user));
        
        
        if($countNote==0) // Pas de titre correspondant -> creation du tableau de retour des mots recherchés.
        {
           $note = $this->createArrayForSearch($value, $depot);
           if(count($note)==1)
           {
               $countNote = 1;
           }
//           if(count($note)==1)
//           {
//               return $this->redirect($this->generateUrl('jclememo_voir', array(
//                'slug' => $note[0]->getSlug()
//                    )));
//           }
        }
        
        if($countNote==1) // Un seul titre correspondant -> affichage de la note.
        {                
            return $this->redirect($this->generateUrl('jclememo_voir', array(
                'slug' => $note[0]->getSlug()
                    )));
        }
        // Si plus d'un titre -> $note conserve les notes ayant le même titre et les affichent.
        
        if($note)
        {
            $pagination = $this->createPagination($note, $request, JCLEMemoConst::Max_Result_Notes, $page);
            
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
        
        public function createArrayForSearch($value, $depot)
        {
            $note=array();
            $user = $this->getUser();
            // Détachement de la recherche mot à mot
            $searches = array_filter(explode(' ',$value));
            $recherche = $depot->recherche($searches, $user);

            foreach ($recherche as $key => $_value)
            {
                $note[$key] = $_value;
            }
            return $note;
        }
        
        public function createPagination($object, Request $request, $max_results, $page=1)
        {
            $paginator  = $this->get('knp_paginator');
            return $pagination = $paginator->paginate(
                    $object,
                    $request->query->get('page', $page)/*page number*/,
                    $max_results /*limit per page*/
                );
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
        
        public function searchNotesByIconAction($page, $iconAlt, Request $request)
        {
            $username = $this->getUser()->getUsername();
            
            $em = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('JCLEMemoBundle:Note');
            $note = $em->findByIcon($iconAlt,$username);
            
            if($note)
            {
                $pagination = $this->createPagination($note, $request, JCLEMemoConst::Max_Result_Notes, $page);
            
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
