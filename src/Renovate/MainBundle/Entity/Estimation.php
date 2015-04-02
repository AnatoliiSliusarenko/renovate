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
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'customer' => $this->getCustomer(),
    			'performer' => $this->getPerformer(),
    			'materialsAmount' => $this->getMaterialsAmount(),
    			'worksAmount' => $this->getWorksAmount(),
    			'totalAmount' => $this->getTotalAmount(),
    			'updated' => $this->getUpdated()->getTimestamp()*1000
    	);
    }
    
    public static function getEstimations($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Estimation")
    	->createQueryBuilder('e');
    
    	$qb->select('e')
    	->addOrderBy('e.updated');
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	
    	if (isset($parameters['from']))
    	{
    		$qb->andWhere('e.updated > :from')
    		->setParameter('from', $parameters['from']);
    	}
    	 
    	if (isset($parameters['to']))
    	{
    		$qb->andWhere('e.updated < :to')
    		->setParameter('to', $parameters['to']);
    	}
    	 
    	if (isset($parameters['search']))
    	{
    		$qb->andWhere($qb->expr()->orX(
    				$qb->expr()->like('e.id', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('e.customer', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('e.performer', $qb->expr()->literal('%'.$parameters['search'].'%'))
    		));
    	}
    	 
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($estimation){
    			return $estimation->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getEstimationsCount($em, $parameters)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Estimation")
    	->createQueryBuilder('e');
    	 
    	$qb->select('COUNT(e.id)');
    	 
    	if (isset($parameters['from']))
    	{
    		$qb->andWhere('e.updated > :from')
    		->setParameter('from', $parameters['from']);
    	}
    	 
    	if (isset($parameters['to']))
    	{
    		$qb->andWhere('e.updated < :to')
    		->setParameter('to', $parameters['to']);
    	}
    	 
    	if (isset($parameters['search']))
    	{
    		$qb->andWhere($qb->expr()->orX(
    				$qb->expr()->like('e.id', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('e.customer', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('e.performer', $qb->expr()->literal('%'.$parameters['search'].'%'))
    		));
    	}
    	 
    	$total = $qb->getQuery()->getSingleScalarResult();
    
    	return $total;
    }
    
    public static function saveEstimation($em, $parameters)
    {
    	if (isset($parameters->id)){
    		$estimation = $em->getRepository("RenovateMainBundle:Estimation")->find($parameters->id);
    	}else{
    		$estimation = new Estimation();
    	}
    	
    	
    	$estimation->setCustomer($parameters->customer);
    	$estimation->setPerformer($parameters->performer);
    	
    	
    	$estimation->setMaterialsAmount(0);
    	$estimation->setWorksAmount(0);
    	$estimation->setTotalAmount(0);
    	
    	$estimation->setUpdated(new \DateTime());
    	
    	$em->persist($estimation);
    	$em->flush();
    	
    	return $estimation;
    }
}
