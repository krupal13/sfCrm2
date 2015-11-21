<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "agent"   = "UserDetailsAgent",
 *  "manager" = "UserDetailsManager",
 *  "client"  = "UserDetailsClient"
 * })
 * 
 * @ORM\Entity()
 * @ORM\Table(name="user_details")
 */
abstract class UserDetails
{
    use Traits\TimestampableTrait;
    
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
     * @ORM\Column(name="first_name", type="string", length=100)
     */
    private $firstName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100)
     */
    private $lastName;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\OneToMany(targetEntity="Alert", mappedBy="user")
     */
    protected $alerts;
    
    /**
     * @var User
     * 
     * @ORM\OneToOne(targetEntity="User", inversedBy="details")
     */
    private $user;

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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return UserDetails
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return UserDetails
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    
    /**
     * Full Name
     * 
     * @return string
     */
    public function getFullName()
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }
    
    /**
     * Add alert
     *
     * @param \AppBundle\Entity\Alert $alert
     *
     * @return UserDetails
     */
    public function addAlert(\AppBundle\Entity\Alert $alert)
    {
        $this->alerts[] = $alert;

        return $this;
    }

    /**
     * Remove alert
     *
     * @param \AppBundle\Entity\Alert $alert
     */
    public function removeAlert(\AppBundle\Entity\Alert $alert)
    {
        $this->alerts->removeElement($alert);
    }

    /**
     * Get alerts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->alerts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserDetails
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
