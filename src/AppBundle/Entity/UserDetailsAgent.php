<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserDetailsAgent
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserDetailsAgentRepository")
 */
class UserDetailsAgent extends UserDetails
{
    /**
     * @var integer
     *
     * @ORM\Column(name="commission", type="integer")
     */
    private $commission;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Agreement", mappedBy="agent")
     */
    private $agreements;
    
    /**
     * @var UserDetailsManager
     * 
     * @ORM\ManyToOne(targetEntity="UserDetailsManager", inversedBy="agents")
     */
    private $manager;
    
    /**
     * Set commission
     *
     * @param integer $commission
     *
     * @return UserDetailsAgent
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Get commission
     *
     * @return integer
     */
    public function getCommission()
    {
        return $this->commission;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->agreements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add agreement
     *
     * @param \AppBundle\Entity\Agreement $agreement
     *
     * @return UserDetailsAgent
     */
    public function addAgreement(\AppBundle\Entity\Agreement $agreement)
    {
        $this->agreements[] = $agreement;

        return $this;
    }

    /**
     * Remove agreement
     *
     * @param \AppBundle\Entity\Agreement $agreement
     */
    public function removeAgreement(\AppBundle\Entity\Agreement $agreement)
    {
        $this->agreements->removeElement($agreement);
    }

    /**
     * Get agreements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgreements()
    {
        return $this->agreements;
    }

    /**
     * Set manager
     *
     * @param \AppBundle\Entity\UserDetailsManager $manager
     *
     * @return UserDetailsAgent
     */
    public function setManager(\AppBundle\Entity\UserDetailsManager $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return \AppBundle\Entity\UserDetailsManager
     */
    public function getManager()
    {
        return $this->manager;
    }
}
