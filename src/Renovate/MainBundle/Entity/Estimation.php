<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estimation
 *
 * @ORM\Table(name="estimations")
 * @ORM\Entity
 */
class Estimation
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
     * @ORM\Column(name="customer", type="string", length=255)
     */
    private $customer;

    /**
     * @var string
     *
     * @ORM\Column(name="performer", type="string", length=255)
     */
    private $performer;

    /**
     * @var float
     *
     * @ORM\Column(name="materials_amount", type="float")
     */
    private $materialsAmount;

    /**
     * @var float
     *
     * @ORM\Column(name="works_amount", type="float")
     */
    private $worksAmount;

    /**
     * @var float
     *
     * @ORM\Column(name="total_amount", type="float")
     */
    private $totalAmount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;


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
     * Set customer
     *
     * @param string $customer
     * @return Estimation
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return string 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set performer
     *
     * @param string $performer
     * @return Estimation
     */
    public function setPerformer($performer)
    {
        $this->performer = $performer;

        return $this;
    }

    /**
     * Get performer
     *
     * @return string 
     */
    public function getPerformer()
    {
        return $this->performer;
    }

    /**
     * Set materialsAmount
     *
     * @param float $materialsAmount
     * @return Estimation
     */
    public function setMaterialsAmount($materialsAmount)
    {
        $this->materialsAmount = $materialsAmount;

        return $this;
    }

    /**
     * Get materialsAmount
     *
     * @return float 
     */
    public function getMaterialsAmount()
    {
        return $this->materialsAmount;
    }

    /**
     * Set worksAmount
     *
     * @param float $worksAmount
     * @return Estimation
     */
    public function setWorksAmount($worksAmount)
    {
        $this->worksAmount = $worksAmount;

        return $this;
    }

    /**
     * Get worksAmount
     *
     * @return float 
     */
    public function getWorksAmount()
    {
        return $this->worksAmount;
    }

    /**
     * Set totalAmount
     *
     * @param float $totalAmount
     * @return Estimation
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return float 
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Estimation
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
