<?php namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "life"    = "AgreementLife",
 *  "vehicle" = "AgreementVehicle",
 *  "estate"  = "AgreementEstate"
 * })
 * 
 * @ORM\Entity()
 * @ORM\Table(name="agreement")
 */
abstract class Agreement
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
     * @ORM\Column(name="number", type="string", length=30)
     */
    private $number;
    
    /**
     * @var string
     * 
     * @Gedmo\Slug(fields={"number"})
     * @ORM\Column(name="slug", type="string", length=40, unique=true)
     */
    private $slug;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="value", type="decimal", scale=2, precision=10)
     */
    private $value;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserDetailsAgent", inversedBy="agreements")
     */
    private $agent;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserDetailsClient", inversedBy="agreements")
     */
    private $client;
    
    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="agreement", cascade={"persist"})
     */
    private $attachments;

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
     * Set number
     *
     * @param string $number
     *
     * @return Agreement
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Agreement
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set agent
     *
     * @param \AppBundle\Entity\UserDetailsAgent $agent
     *
     * @return Agreement
     */
    public function setAgent(\AppBundle\Entity\UserDetailsAgent $agent = null)
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Get agent
     *
     * @return \AppBundle\Entity\UserDetailsAgent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\UserDetailsClient $client
     *
     * @return Agreement
     */
    public function setClient(\AppBundle\Entity\UserDetailsClient $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\UserDetailsClient
     */
    public function getClient()
    {
        return $this->client;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attachments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add attachment
     *
     * @param \AppBundle\Entity\Attachment $attachment
     *
     * @return Agreement
     */
    public function addAttachment(\AppBundle\Entity\Attachment $attachment)
    {
        $this->attachments[] = $attachment;
        $attachment->setAgreement($this);

        return $this;
    }

    /**
     * Remove attachment
     *
     * @param \AppBundle\Entity\Attachment $attachment
     */
    public function removeAttachment(\AppBundle\Entity\Attachment $attachment)
    {
        $this->attachments->removeElement($attachment);
    }

    /**
     * Get attachments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttachments()
    {
        return $this->attachments;
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
}
