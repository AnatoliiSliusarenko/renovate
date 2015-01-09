<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Job
 *
 * @ORM\Table(name="jobs")
 * @ORM\Entity
 */
class Job
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


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
     * Set name
     *
     * @param string $name
     * @return Job
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
     * Set description
     *
     * @param string $description
     * @return Job
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Job
     */
    public function setCreated($created)
    {
    	$this->created = $created;
    
    	return $this;
    }
    
    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
    	return $this->created;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'name' => $this->getName(),
    			'description' => $this->getDescription(),
    			'created' => $this->getCreated()
    	);
    }
    
    public static function getAllJobs($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Job")
    			 ->createQueryBuilder('j');
    	
    	$qb->select('j')
    	   ->orderBy('j.created', 'DESC');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($job){
    			return $job->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getJobs($em, $offset, $limit, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Job")
    	->createQueryBuilder('j');
    	 
    	$qb->select('j')
    	   ->orderBy('j.created', 'DESC')
    	   ->setFirstResult($offset)
		   ->setMaxResults($limit);
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($job){
    			return $job->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getJobsCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Job")
    				->createQueryBuilder('j')
    				->select('COUNT(j.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addJob($em, $parameters)
    {
    	$job = new Job();
    	$job->setName($parameters->name);
    	$job->setDescription($parameters->description);
    	$job->setCreated(new \DateTime());
    	
    	$em->persist($job);
    	$em->flush();
    	
    	return $job;
    }
    
    public static function removeJobById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Job', 'j')
    	->where('j.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editJobById($em, $id, $parameters)
    {
    	$job = $em->getRepository("RenovateMainBundle:Job")->find($id);
    	
    	$job->setName($parameters->name);
    	$job->setDescription($parameters->description);
    	
    	$em->persist($job);
    	$em->flush();
    	
    	return $job;
    }
}
