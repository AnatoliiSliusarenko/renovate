<?php

namespace Ordis\MainBundle\Entity;

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
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'name' => $this->getName(),
    			'description' => $this->getDescription()
    	);
    }
    
    public static function getAllJobs($em, $inArray = false)
    {
    	$qb = $em->getRepository("OrdisMainBundle:Job")
    			 ->createQueryBuilder('j');
    	
    	$qb->select('j');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($job){
    			return $job->getInArray();
    		}, $result);
    	}else return $result;
    }
}
