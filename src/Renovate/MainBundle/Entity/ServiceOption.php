<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceOption
 *
 * @ORM\Table(name="service_options")
 * @ORM\Entity
 */
class ServiceOption
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="serviceOptions")
     * @ORM\JoinColumn(name="serviceid")
     * @var Service
     */
    private $service;
    
    /**
     * @ORM\OneToMany(targetEntity="ServicePrice", mappedBy="option")
     * @var array
     */
    private $servicePrices;

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
     * Set serviceid
     *
     * @param integer $serviceid
     * @return ServiceOption
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
     * @return ServiceOption
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
     * Set name
     *
     * @param string $name
     * @return ServiceOption
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set service
     *
     * @param \Renovate\MainBundle\Entity\Service $service
     * @return ServiceOption
     */
    public function setService(\Renovate\MainBundle\Entity\Service $service = null)
    {
    	$this->service = $service;
    
    	return $this;
    }
    
    /**
     * Get service
     *
     * @return \Renovate\MainBundle\Entity\Service
     */
    public function getService()
    {
    	return $this->service;
    }
    
    /**
     * Add servicePrices
     *
     * @param \Renovate\MainBundle\Entity\ServicePrice $servicePrices
     * @return ServiceOption
     */
    public function addServicePrice(\Renovate\MainBundle\Entity\ServicePrice $servicePrices)
    {
        $this->servicePrices[] = $servicePrices;

        return $this;
    }

    /**
     * Remove servicePrices
     *
     * @param \Renovate\MainBundle\Entity\ServicePrice $servicePrices
     */
    public function removeServicePrice(\Renovate\MainBundle\Entity\ServicePrice $servicePrices)
    {
        $this->servicePrices->removeElement($servicePrices);
    }

    /**
     * Get servicePrices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServicePrices()
    {
        return $this->servicePrices;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->servicePrices = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'serviceid' => $this->getServiceid(),
    			'value' => $this->getValue(),
    			'name' => $this->getName()
    	);
    }
}
