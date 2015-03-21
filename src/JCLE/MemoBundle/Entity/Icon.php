<?php

namespace JCLE\MemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Security\Acl\Exception\Exception;

/**
 * Icon
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JCLE\MemoBundle\Entity\IconRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Icon
{
/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     * @Assert\Length(
     *      min = "0",
     *      max = "255",
     *      maxMessage = "Le texte alternatif de l'image ne doit pas dépassé {{ limit }} caractères"
     * )
     */
    private $alt;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="JCLE\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $createur;
    
    /**
     *
     * @Assert\File(
     *      mimeTypes = {"image/png"}
     * )
     */
    private $fichier;
    
    private $tempFilename;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="JCLE\MemoBundle\Entity\Note", mappedBy="icon", cascade={"remove"} )
     * @Assert\Valid()
     */
    private $notes;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }
    
    // accesseur modifié en rajoutant la condition si un fichier existe déja
    public function setfichier(UploadedFile $fichier)
    {
        if($fichier->getMimeType()=="image/png")
        {
            $this->fichier = $fichier;
            // si aucun fichier renseigné déja pour cet objet
            if(null !== $this->alt)
            {
                $this->alt = null;
            }
        }else{
            throw new Exception('Format du fichier incorrect. Votre image doit être au format .png');
        }
        

        return $this;
    }

    /**
     *
     * @return string 
     */
    public function getfichier()
    {
        return $this->fichier;
    }
    
    // fonction qui suit créée de toute piece -> Equivalent aux Triggers
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // S'il n'y a pas de fichier ( champ facultatif )
        if ( null === $this->fichier)
        {
            return;
        }

        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
        // tout en retirant le .png
        $this->alt = str_replace('.png','',strtolower($this->fichier->getClientOriginalName()));
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // Si pas de fichier
        if (null === $this->fichier)
        {
            return;
        }
        
        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
            if (file_exists($oldFile)) {
            unlink($oldFile); // unlink supprime
            }
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->fichier->move(
            $this->getUploadRootDir(), // Le répertoire de destination
            $this->id.'.png'   // Le nom du fichier à créer, ici « id.png »
        );
        
        // Redimensionne l'icone
        $this->resizePng($this->getUploadRootDir().'/'.$this->id.'.png', 50, 50);
    
    }
    
    
    public function resizePng($pathFile, $largeur, $hauteur)
    {
        $source = imagecreatefrompng($pathFile); // La photo est la source
        $destination = imagecreatetruecolor($largeur, $hauteur); // On crée la miniature vide

        // Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
        $largeur_source = imagesx($source);
        $hauteur_source = imagesy($source);
        $largeur_destination = imagesx($destination);
        $hauteur_destination = imagesy($destination);
        
        // Ne redimensionne que si c'est plus grand ( sinon floutage horrible )
        if($largeur_source>$largeur_destination)
        {
            // Prise en compte de la transparence du png
            imagealphablending($destination,false);
            imagesavealpha($destination,true);
            // On crée la miniature
            imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
            // On remplace l'image d'origine par la miniature
            imagepng($destination, $pathFile);
        }
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.png';
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En post remove, on a pas acces à l'id, on utilise notre nom sauvegardé
        if(file_exists($this->tempFilename))
        {
            // On supprime le fichier
            unlink($this->tempFilename);
        }
    }
    
    public function getUploadDir()
    {
        return 'uploads/icon';
    }
    
    public function getUploadRootDir()
    {
        return __DIR__.'/../../../../www/'.$this->getUploadDir();
    }
    
    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getId().'.png';
    }



    /**
     * Set createur
     *
     * @param \JCLE\UserBundle\Entity\User $createur
     * @return Icon
     */
    public function setCreateur(\JCLE\UserBundle\Entity\User $createur = null)
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur
     *
     * @return \JCLE\UserBundle\Entity\User 
     */
    public function getCreateur()
    {
        return $this->createur;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add notes
     *
     * @param \JCLE\MemoBundle\Entity\Note $notes
     * @return Icon
     */
    public function addNote(\JCLE\MemoBundle\Entity\Note $notes)
    {
        $this->notes[] = $notes;
        
        $notes->setIcon($this);

        return $this;
    }

    /**
     * Remove notes
     *
     * @param \JCLE\MemoBundle\Entity\Note $notes
     */
    public function removeNote(\JCLE\MemoBundle\Entity\Note $notes)
    {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
