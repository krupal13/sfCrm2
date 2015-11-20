<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AgreementVehile
 *
 * @ORM\Entity
 */
class AgreementVehicle extends Agreement
{
    /**
     * @var string
     *
     * @ORM\Column(name="vehicle", type="string", length=100)
     */
    private $vehicle;

    /**
     * Set vehicle
     *
     * @param string $vehicle
     *
     * @return AgreementVehicle
     */
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * Get vehicle
     *
     * @return string
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AgreementVehicle
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return AgreementVehicle
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
