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
     * @ORM\ManyToOne(targetEntity="Estimation", inversedBy="estimationCosts")
     * @ORM\JoinColumn(name="estimationid")
     * @var EstimationCost
     */
    private $estimation;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cost", inversedBy="estimationCosts")
     * @ORM\JoinColumn(name="costid")
     * @var Cost
     */
    private $cost;
    
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
    
    public function calculateTotal()
    {
    	$this->setTotal($this->getCost()->getPrice()*$this->getCount());
    }

    /**
     * Set estimation
     *
     * @param \Renovate\MainBundle\Entity\Estimation $estimation
     * @return EstimationCost
     */
    public function setEstimation(\Renovate\MainBundle\Entity\Estimation $estimation = null)
    {
        $this->estimation = $estimation;

        return $this;
    }

    /**
     * Get estimation
     *
     * @return \Renovate\MainBundle\Entity\Estimation 
     */
    public function getEstimation()
    {
        return $this->estimation;
    }
    
    /**
     * Set cost
     *
     * @param \Renovate\MainBundle\Entity\Cost $cost
     * @return EstimationCost
     */
    public function setCost(\Renovate\MainBundle\Entity\Cost $cost = null)
    {
    	$this->cost = $cost;
    
    	return $this;
    }
    
    /**
     * Get cost
     *
     * @return \Renovate\MainBundle\Entity\Cost
     */
    public function getCost()
    {
    	return $this->cost;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'estimationid' => $this->getEstimationid(),
    			'costid' => $this->getCostid(),
    			'count' => $this->getCount(),
    			'total' => $this->getTotal(),
    			'cost' => $this->getCost()->getInArray()
    	);
    }
    
    public static function addEstimationCost($em, $parameters)
    {
    	$estimationCost = $em->getRepository("RenovateMainBundle:EstimationCost")->findOneBy(array("estimationid"=>$parameters->estimationid,"costid"=>$parameters->costid));
    	
    	if ($estimationCost != NULL) return false;
    	
    	$estimation = $em->getRepository("RenovateMainBundle:Estimation")->find($parameters->estimationid);
    	$cost = $em->getRepository("RenovateMainBundle:Cost")->find($parameters->costid);
    	
    	$estimationCost = new EstimationCost();
    	$estimationCost->setEstimationid($estimation->getId());
    	$estimationCost->setEstimation($estimation);
    	$estimationCost->setCostid($cost->getId());
    	$estimationCost->setCost($cost);
    	$estimationCost->setCount(1);
    	$estimationCost->setTotal($cost->getPrice());
    	
    	$em->persist($estimationCost);
    	$em->flush();
    	
    	$estimationCost->getEstimation()->calculateAmount();
    	
    	$em->persist($estimationCost->getEstimation());
    	$em->flush();
    
    	return $estimationCost;
    }
    
    public static function removeEstimationCostById($em, $id)
    {
    	$estimationCost = $em->getRepository("RenovateMainBundle:EstimationCost")->find($id);
    	
    	$em->remove($estimationCost);
    	$em->flush();
    	
    	$estimationCost->getEstimation()->calculateAmount();
    	 
    	$em->persist($estimationCost->getEstimation());
    	$em->flush();
    
    	return true;
    }
    
    public static function editEstimationCostById($em, $id, $parameters)
    {
    	$estimationCost = $em->getRepository("RenovateMainBundle:EstimationCost")->find($id);
    
    	$estimationCost->setCount($parameters->count);
    	$estimationCost->calculateTotal();
    	$em->persist($estimationCost);
    	$em->flush();
    	
    	$estimationCost->getEstimation()->calculateAmount();
    	
    	$em->persist($estimationCost->getEstimation());
    	$em->flush();
    
    	return $estimationCost;
    }
}
