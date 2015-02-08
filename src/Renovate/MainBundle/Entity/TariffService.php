<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TariffService
 *
 * @ORM\Table(name="tariffs_services")
 * @ORM\Entity
 */
class TariffService
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
     * @var integer
     *
     * @ORM\Column(name="tariffid", type="integer")
     */
    private $tariffid;

    /**
     * @var integer
     *
     * @ORM\Column(name="serviceid", type="integer")
     */
    private $serviceid;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;


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
     * Set tariffid
     *
     * @param integer $tariffid
     * @return TariffService
     */
    public function setTariffid($tariffid)
    {
        $this->tariffid = $tariffid;

        return $this;
    }

    /**
     * Get tariffid
     *
     * @return integer 
     */
    public function getTariffid()
    {
        return $this->tariffid;
    }

    /**
     * Set serviceid
     *
     * @param integer $serviceid
     * @return TariffService
     */
    public function setServiceid($serviceid)
    {
        $this->serviceid = $serviceid;

        return $this;
    }

    /**
     * Get serviceid
     *
     * @return integer 
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return TariffService
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
}
