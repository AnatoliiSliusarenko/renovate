<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstimationCost
 *
 * @ORM\Table(name="estimations_costs")
 * @ORM\Entity
 */
class EstimationCost
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
     * @ORM\Column(name="estimationid", type="integer")
     */
    private $estimationid;

    /**
     * @var integer
     *
     * @ORM\Column(name="costid", type="integer")
     */
    private $costid;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;


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
     * Set estimationid
     *
     * @param integer $estimationid
     * @return EstimationCost
     */
    public function setEstimationid($estimationid)
    {
        $this->estimationid = $estimationid;

        return $this;
    }

    /**
     * Get estimationid
     *
     * @return integer 
     */
    public function getEstimationid()
    {
        return $this->estimationid;
    }

    /**
     * Set costid
     *
     * @param integer $costid
     * @return EstimationCost
     */
    public function setCostid($costid)
    {
        $this->costid = $costid;

        return $this;
    }

    /**
     * Get costid
     *
     * @return integer 
     */
    public function getCostid()
    {
        return $this->costid;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return EstimationCost
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return EstimationCost
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }
}
