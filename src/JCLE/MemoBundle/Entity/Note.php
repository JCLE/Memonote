<?php

namespace JCLE\MemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Note
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JCLE\MemoBundle\Entity\NoteRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Note
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
     * @ORM\Column(name="titre", type="string", length=125)
     * @Assert\Length(
     *      min = "2",
     *      max = "125",
     *      minMessage = "Votre titre doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre titre ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $titre;
    
    /**
     *
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\Length(
     *      min = "10",
     *      minMessage = "La description doit faire au minimum {{ limit }} caractères"
     * )
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="JCLE\MemoBundle\Entity\Icon", inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $icon;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Votre tag ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $tag;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="JCLE\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $createur;
    
    /**
     *
     * @ORM\ManyToMany(targetEntity="JCLE\MemoBundle\Entity\Note", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     */
    private $notes;
    
    
    
    
    
//    /**
//    * @ORM\ManyToMany(targetEntity="JCLE\MemoBundle\Entity\Note", mappedBy="noteFrom")
//    */
//   protected $noteTo;
//
//   /**
//    * @ORM\ManyToMany(targetEntity="Note", inversedBy="noteTo")
//    * @ORM\JoinTable(name="liens",
//    *      joinColumns={@ORM\JoinColumn(name="note_id", referencedColumnName="id")},
//    *      inverseJoinColumns={@ORM\JoinColumn(name="note_link", referencedColumnName="id")}
//    *      )
//    */
//   protected $noteFrom;
   
   
   



    
    public function __construct() {
        $this->date = new \Datetime();
        $this->notes = new ArrayCollection();
    }

    
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
     * Set titre
     *
     * @param string $titre
     * @return Note
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Note
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Note
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set icon
     *
     * @param \JCLE\MemoBundle\Entity\Icon $icon
     * @return Note
     */
    public function setIcon(\JCLE\MemoBundle\Entity\Icon $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return \JCLE\MemoBundle\Entity\Icon 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Note
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Set createur
     *
     * @param \JCLE\UserBundle\Entity\User $createur
     * @return Note
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
     * Set tag
     *
     * @param string $tag
     * @return Note
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }


    /**
     * Add notes
     *
     * @param \JCLE\MemoBundle\Entity\Note $notes
     * @return Note
     */
    public function addNote(\JCLE\MemoBundle\Entity\Note $notes)
    {
        $this->notes[] = $notes;

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
