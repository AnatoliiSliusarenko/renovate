<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity
 */
class Article
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="articles")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="articles")
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
    			'description' => $this->getDescription(),
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'document' => $this->getDocument()->getInArray(),
    			'user' => $this->getUser()->getInArray()
    	);
    }
    
    public static function getAllArticles($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Article")
    			 ->createQueryBuilder('a');
    	
    	$qb->select('a')
    	   ->orderBy('a.created', 'DESC');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($article){
    			return $article->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getArticles($em, $offset, $limit, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Article")
    	->createQueryBuilder('a');
    	 
    	$qb->select('a')
    	   ->orderBy('a.created', 'DESC')
    	   ->setFirstResult($offset)
		   ->setMaxResults($limit);
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($article){
    			return $article->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getArticlesCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Article")
    				->createQueryBuilder('a')
    				->select('COUNT(a.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addArticle($em, \Renovate\MainBundle\Entity\User $user, $parameters)
    {
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$article = new Article();
    	$article->setName($parameters->name);
    	$article->setUserid($user->getId());
    	$article->setUser($user);
    	$article->setDocumentid($parameters->documentid);
    	$article->setDocument($document);
    	$article->setDescription($parameters->description);
    	$article->setCreated(new \DateTime());
    	
    	$em->persist($article);
    	$em->flush();
    	
    	return $article;
    }
    
    public static function removeArticleById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Article', 'a')
    	->where('a.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editArticleById($em, $id, $parameters)
    {
    	$article = $em->getRepository("RenovateMainBundle:Article")->find($id);
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$article->setDocumentid($parameters->documentid);
    	$article->setDocument($document);
    	$article->setName($parameters->name);
    	$article->setDescription($parameters->description);
    	
    	$em->persist($article);
    	$em->flush();
    	
    	return $article;
    }
}
