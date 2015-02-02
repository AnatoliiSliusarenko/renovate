<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="services")
 * @ORM\Entity
 */
class Service
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
     * @ORM\Column(name="roleid", type="integer")
     */
    private $roleid;

    /**
     * @var integer
     *
     * @ORM\Column(name="categoryid", type="integer")
     */
    private $categoryid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
    
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="services")
     * @ORM\JoinColumn(name="roleid")
     * @var Role
     */
    private $role;
    
    /**
     * @ORM\ManyToOne(targetEntity="ServiceCategory", inversedBy="services")
     * @ORM\JoinColumn(name="categoryid")
     * @var ServiceCategory
     */
    private $category;
    
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
     * Set roleid
     *
     * @param integer $roleid
     * @return Service
     */
    public function setRoleid($roleid)
    {
        $this->roleid = $roleid;

        return $this;
    }

    /**
     * Get roleid
     *
     * @return integer 
     */
    public function getRoleid()
    {
        return $this->roleid;
    }

    /**
     * Set categoryid
     *
     * @param integer $categoryid
     * @return Service
     */
    public function setCategoryid($categoryid)
    {
        $this->categoryid = $categoryid;

        return $this;
    }

    /**
     * Get categoryid
     *
     * @return integer 
     */
    public function getCategoryid()
    {
        return $this->categoryid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Service
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
     * Set price
     *
     * @param float $price
     * @return Service
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Service
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Service
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
     * Set role
     *
     * @param \Renovate\MainBundle\Entity\Role $role
     * @return Service
     */
    public function setRole(\Renovate\MainBundle\Entity\Role $role = null)
    {
    	$this->role = $role;
    
    	return $this;
    }
    
    /**
     * Get role
     *
     * @return \Renovate\MainBundle\Entity\Role
     */
    public function getRole()
    {
    	return $this->role;
    }
    
    /**
     * Set category
     *
     * @param \Renovate\MainBundle\Entity\ServiceCategory $category
     * @return Service
     */
    public function setCategory(\Renovate\MainBundle\Entity\ServiceCategory $category = null)
    {
    	$this->category = $category;
    
    	return $this;
    }
    
    /**
     * Get category
     *
     * @return \Renovate\MainBundle\Entity\ServiceCategory
     */
    public function getCategory()
    {
    	return $this->category;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'roleid' => $this->getRoleid(),
    			'categoryid' => $this->getCategoryid(),
    			'name' => $this->getName(),
    			'price' => $this->getPrice(),
    			'type' => $this->getType(),
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'role' => $this->getRole()->getInArray(),
    			'category' => $this->getCategory()->getInArray()
    	);
    }
    
    public static function getAllServices($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Service")
    	->createQueryBuilder('s');
    	 
    	$qb->select('s')
    	->orderBy('s.created', 'DESC');
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($service){
    			return $service->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getServices($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Service")
    	->createQueryBuilder('s');
    
    	$qb->select('s')
    	->orderBy('s.created', 'DESC');
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	
    	if (isset($parameters['role']))
    	{
    		$qb->andWhere("s.roleid = :roleid")
    		->setParameter("roleid", $parameters['role']);
    	}
    	
    	if (isset($parameters['category']))
    	{
    		$qb->andWhere("s.categoryid = :categoryid")
    		->setParameter("categoryid", $parameters['category']);
    	}
    	
    	if (isset($parameters['type']))
    	{
    		$qb->andWhere("s.type = :type")
    		->setParameter("type", $parameters['type']);
    	}
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($service){
    			return $service->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getServicesCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Service")
    	->createQueryBuilder('s')
    	->select('COUNT(s.id)')
    	->getQuery();
    	 
    	$total = $query->getSingleScalarResult();
    	 
    	return $total;
    }
}
