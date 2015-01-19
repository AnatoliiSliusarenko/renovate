<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Result
 *
 * @ORM\Table(name="results")
 * @ORM\Entity
 */
class Result
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
     * @var integer
     *
     * @ORM\Column(name="documentid", type="integer")
     */
    private $documentid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name_translit", type="string", length=255)
     */
    private $nameTranslit;

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="results")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="results")
     * @ORM\JoinColumn(name="documentid")
     * @var Document
     */
    private $document;

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
     * @return Result
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
     * Set documentid
     *
     * @param integer $documentid
     * @return Result
     */
    public function setDocumentid($documentid)
    {
    	$this->documentid = $documentid;
    
    	return $this;
    }
    
    /**
     * Get documentid
     *
     * @return integer
     */
    public function getDocumentid()
    {
    	return $this->documentid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Result
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
     * Set nameTranslit
     *
     * @param string $nameTranslit
     * @return Result
     */
    public function setNameTranslit($nameTranslit)
    {
    	$this->nameTranslit = $nameTranslit;
    
    	return $this;
    }
    
    /**
     * Get nameTranslit
     *
     * @return string
     */
    public function getNameTranslit()
    {
    	return $this->nameTranslit;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Result
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
     * @return Result
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
     * Set user
     *
     * @param \Renovate\MainBundle\Entity\User $user
     * @return Result
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
    
    /**
     * Set document
     *
     * @param \Renovate\MainBundle\Entity\Document $document
     * @return Result
     */
    public function setDocument(\Renovate\MainBundle\Entity\Document $document = null)
    {
    	$this->document = $document;
    
    	return $this;
    }
    
    /**
     * Get document
     *
     * @return \Renovate\MainBundle\Entity\Document
     */
    public function getDocument()
    {
    	return $this->document;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'userid' => $this->getUserid(),
    			'documentid' => $this->getDocumentid(),
    			'name' => $this->getName(),
    			'nameTranslit' => $this->getNameTranslit(),
    			'description' => $this->getDescription(),
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'document' => $this->getDocument()->getInArray(),
    			'user' => $this->getUser()->getInArray()
    	);
    }
    
    public static function getAllResults($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Result")
    			 ->createQueryBuilder('r');
    	
    	$qb->select('r')
    	   ->orderBy('r.created', 'DESC');
    	
    	$results = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($result){
    			return $result->getInArray();
    		}, $results);
    	}else return $results;
    }
    
    public static function getResults($em, $offset, $limit, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Result")
    	->createQueryBuilder('r');
    	 
    	$qb->select('r')
    	   ->orderBy('r.created', 'DESC')
    	   ->setFirstResult($offset)
		   ->setMaxResults($limit);
    	 
    	$results = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($result){
    			return $result->getInArray();
    		}, $results);
    	}else return $results;
    }
    
    public static function getResultsCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Result")
    				->createQueryBuilder('r')
    				->select('COUNT(r.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addResult($em, $transliterater, \Renovate\MainBundle\Entity\User $user, $parameters)
    {
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$result = new Result();
    	$result->setName($parameters->name);
    	$result->setNameTranslit($transliterater->transliterate($parameters->name));
    	$result->setUserid($user->getId());
    	$result->setUser($user);
    	$result->setDocumentid($parameters->documentid);
    	$result->setDocument($document);
    	$result->setDescription($parameters->description);
    	$result->setCreated(new \DateTime());
    	
    	$em->persist($result);
    	$em->flush();
    	
    	return $result;
    }
    
    public static function removeResultById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Result', 'r')
    	->where('r.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editResultById($em, $transliterater, $id, $parameters)
    {
    	$result = $em->getRepository("RenovateMainBundle:Result")->find($id);
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$result->setDocumentid($parameters->documentid);
    	$result->setDocument($document);
    	$result->setName($parameters->name);
    	$result->setNameTranslit($transliterater->transliterate($parameters->name));
    	$result->setDescription($parameters->description);
    	
    	$em->persist($result);
    	$em->flush();
    	
    	return $result;
    }
}
