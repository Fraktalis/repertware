<?php

namespace Vincentale\UserBundle\Entity;

// Bundle utilisateur
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var int
     *
     * @ORM\Column(name="tel", type="integer",nullable=true)
     */
    protected $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="website",type="string",nullable=true)
     */
    protected $website;

    /**
     * @var string
     *
     * @ORM\Column(name="adress",type="string",nullable=true)
     */
    protected $adress;
    /**
     * @var string
     *
     * @ORM\Column(name="nom",type="string",nullable=true)
     */
    protected $nom;
    /**
     * @var string
     *
     * @ORM\Column(name="prenom",type="string",nullable=true)
     */
    protected $prenom;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="contactOf")
     * @ORM\JoinTable(name="contacts",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contact_user_id", referencedColumnName="id")}
     *      )
     */
    protected $myContacts;


    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myContacts")
     */
    protected $contactOf;

    public function __construct() {
        parent::__construct();
        $this->myContacts = new ArrayCollection();
        $this->contactOf = new ArrayCollection;
    }

    /**
     * Set tel
     *
     * @param integer $tel
     *
     * @return User
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return integer
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return User
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }


    /**
     * Set adress
     *
     * @param string $adress
     *
     * @return User
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Add contactId
     *
     * @param integer $id
     *
     * @return User
     */
    public function addContact(User $user)
    {
        if ($this->getId() !== $user->getId())
            $this->myContacts[] = $user;
        return $this;

    }   /**
     * Del contactId
     *
     * @param integer $id
     *
     * @return User
     */
    public function delContact(User $user) {
        $this->myContacts->removeElement($user);
        return $this;
        }

    /**
     * Get contacts
     *
     * @return array
     */
    public function getContacts() {
        return $this->myContacts;
    }

    public function hasContact(User $user) {
        return $this->myContacts->contains($user);
    }

}
