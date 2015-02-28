<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="tasks")
 * @ORM\Entity
 */
class Task
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
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;
    
    /**
     * @var boolean
     * @ORM\Column(name="done", type="boolean")
     */
    private $done;
    
    /**
     * @var text
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
     * @var datetime
     *
     * @ORM\Column(name="finished", type="datetime")
     */
    private $finished;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="jobs")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
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
     * Set userid
     *
     * @param integer $userid
     * @return Task
     */
    public function setUserid($userid)
    {
    	$this->userid = $userid;
    
    	return $this;
    }
    
    /**
     * Get userid
     *
     * @return integer
     */
    public function getUserid()
    {
    	return $this->userid;
    }
    
    /**
     * Set done
     *
     * @param boolean $done
     * @return Task
     */
    public function setDone($done)
    {
    	$this->done = $done;
    
    	return $this;
    }
    
    /**
     * Get done
     *
     * @return boolean
     */
    public function getDone()
    {
    	return $this->done;
    }
    
    /**
     * Set description
     *
     * @param string $description
     * @return Task
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
     * @return Task
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
    
    /**
     * Set finished
     *
     * @param \DateTime $finished
     * @return Task
     */
    public function setFinished($finished)
    {
    	$this->finished = $finished;
    
    	return $this;
    }
    
    /**
     * Get finished
     *
     * @return \DateTime
     */
    public function getFinished()
    {
    	return $this->finished;
    }
    
    /**
     * Set user
     *
     * @param \Renovate\MainBundle\Entity\User $user
     * @return Task
     */
    public function setUser(\Renovate\MainBundle\Entity\User $user = null)
    {
    	$this->user = $user;
    
    	return $this;
    }
    
    /**
     * Get user
     *
     * @return \Renovate\MainBundle\Entity\User
     */
    public function getUser()
    {
    	return $this->user;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'userid' => $this->getUserid(),
    			'done' => $this->getDone(),
    			'description' => $this->getDescription(),
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'finished' => ($this->getFinished() != null) ? $this->getFinished()->getTimestamp()*1000 : null,
    			'user' => $this->getUser()->getInArray()
    	);
    }
    
    public static function getTasks($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Task")
    	->createQueryBuilder('t');
    	 
    	$qb->select('t')
    	   ->orderBy('t.created', 'DESC');
    	
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		   ->setMaxResults($parameters['limit']);
    	}
    	
    	if (isset($parameters['from']))
    	{
    		$qb->andWhere('t.created > :from')
    		->setParameter('from', $parameters['from']);
    	}
    	
    	if (isset($parameters['to']))
    	{
    		$qb->andWhere('t.created < :to')
    		->setParameter('to', $parameters['to']);
    	}

    	if (isset($parameters['userid']))
    	{
    		$qb->andWhere('t.userid = :userid')
    		->setParameter('userid', $parameters['userid']);
    	}
    	
    	if (isset($parameters['done']))
    	{
    		$qb->andWhere('t.done = :done')
    		   ->setParameter('done', $parameters['done']);
    	}
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($task){
    			return $task->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getTasksCount($em, $parameters)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Task")
    	->createQueryBuilder('t');
    	
    	$qb->select('COUNT(t.id)');
    	
    	if (isset($parameters['from']))
    	{
    		$qb->andWhere('t.created > :from')
    		->setParameter('from', $parameters['from']);
    	}
    	 
    	if (isset($parameters['to']))
    	{
    		$qb->andWhere('t.created < :to')
    		->setParameter('to', $parameters['to']);
    	}
    	
    	if (isset($parameters['userid']))
    	{
    		$qb->andWhere('t.userid = :userid')
    		->setParameter('userid', $parameters['userid']);
    	}
    	 
    	if (isset($parameters['done']))
    	{
    		$qb->andWhere('t.done = :done')
    		->setParameter('done', $parameters['done']);
    	}
    	
    	$total = $qb->getQuery()->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addTask($em, $parameters)
    {
    	$user = $em->getRepository("RenovateMainBundle:User")->find($parameters->userid);
    	
    	$task = new Task();
    	$task->setDescription($parameters->description);
    	$task->setUserid($user->getId());
    	$task->setUser($user);
    	$task->setDone(false);
    	$task->setCreated(new \DateTime());
    	
    	$em->persist($task);
    	$em->flush();
    	
    	return $task;
    }
    
    public static function removeTaskById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Task', 't')
    	->where('t.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editTaskById($em, $id, $parameters)
    {
    	$task = $em->getRepository("RenovateMainBundle:Task")->find($id);
    	$user = $em->getRepository("RenovateMainBundle:User")->find($parameters->userid);
    	
    	$task->setDescription($parameters->description);
    	$task->setUserid($user->getId());
    	$task->setUser($user);
    	
    	$em->persist($task);
    	$em->flush();
    	
    	return $task;
    }
    
    public static function finishTaskById($em, $id)
    {
    	$task = $em->getRepository("RenovateMainBundle:Task")->find($id);
    	 
    	$task->setDone(true);
    	$task->setFinished(new \DateTime());
    	 
    	$em->persist($task);
    	$em->flush();
    	 
    	return $task;
    }
}
