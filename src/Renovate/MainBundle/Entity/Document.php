<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document
 *
 * @ORM\Table(name="documents")
 * @ORM\Entity
 */
class Document 
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank;
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     * @Assert\NotBlank;
     */
    private $path;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uploaded", type="datetime")
     */
    private $uploaded;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file = null;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="documents")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
    /**
     * @ORM\OneToMany(targetEntity="Job", mappedBy="document")
     * @var array
     */
    private $jobs;
    
    /**
     * @ORM\OneToMany(targetEntity="News", mappedBy="document")
     * @var array
     */
    private $news;
    
    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="document")
     * @var array
     */
    private $results;
    
    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="document")
     * @var array
     */
    private $articles;
    
    private static $fileTypes = array('doc','docx','xls','xlsx','jpg','jpeg','gif','png','avi','pdf','mp3', 'zip','txt','xml','xps','rtf','odt','htm','html','ods');
    
    private static $SALT = '767usghbe7h8#@4b32n32)_$)&N_*$)*$^@$JHN_$_$*N$@($)@*NH';
    
    
    
    function __construct($user)
    {
    	$this->setUserid($user->getId());
    	$this->setUser($user);
    	$this->setUploaded(new \DateTime());
    }
    
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
     * @return Document
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
     * Set name
     *
     * @param string $name
     * @return Document
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
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    
    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Set uploaded
     *
     * @param \DateTime $uploaded
     * @return Document
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;
        return $this;
    }
    /**
     * Get uploaded
     *
     * @return \DateTime 
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }
    
    /**
     * Sets file
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null, $timestamp, $token)
    {
    	$verifyToken = md5(self::$SALT . $timestamp);
    	if ($token !== $verifyToken) return false;
    	 
    	$this->file = $file;
    	 
    	$this->path = $this->getFile()->getClientOriginalName();
    	 
    	$this->name = $this->getFile()->getClientOriginalName();
    	return true;
    }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
    	return $this->file;
    }
    
    /**
     * Set user
     *
     * @param \Intranet\MainBundle\Entity\User $user
     * @return Document
     */
    public function setUser(\Renovate\MainBundle\Entity\User $user = null)
    {
    	$this->user = $user;
    	return $this;
    }
    
    /**
     * Get user
     *
     * @return \Intranet\MainBundle\Entity\User
     */
    public function getUser()
    {
    	return $this->user;
    }
    
    /**
     * Add jobs
     *
     * @param \Renovate\MainBundle\Entity\Job $jobs
     * @return Document
     */
    public function addJob(\Renovate\MainBundle\Entity\Job $jobs)
    {
    	$this->jobs[] = $jobs;
    
    	return $this;
    }
    
    /**
     * Remove jobs
     *
     * @param \Renovate\MainBundle\Entity\Job $jobs
     */
    public function removeJob(\Renovate\MainBundle\Entity\Job $jobs)
    {
    	$this->jobs->removeElement($jobs);
    }
    
    /**
     * Get jobs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJobs()
    {
    	return $this->jobs;
    }
    
    /**
     * Add news
     *
     * @param \Renovate\MainBundle\Entity\News $news
     * @return Document
     */
    public function addNews(\Renovate\MainBundle\Entity\News $news)
    {
    	$this->news[] = $news;
    
    	return $this;
    }
    
    /**
     * Remove news
     *
     * @param \Renovate\MainBundle\Entity\News $news
     */
    public function removeNews(\Renovate\MainBundle\Entity\News $news)
    {
    	$this->news->removeElement($news);
    }
    
    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
    	return $this->news;
    }
    
    /**
     * Add results
     *
     * @param \Renovate\MainBundle\Entity\Result $results
     * @return Document
     */
    public function addResult(\Renovate\MainBundle\Entity\Result $results)
    {
    	$this->results[] = $results;
    
    	return $this;
    }
    
    /**
     * Remove results
     *
     * @param \Renovate\MainBundle\Entity\Result $results
     */
    public function removeResult(\Renovate\MainBundle\Entity\Result $results)
    {
    	$this->results->removeElement($results);
    }
    
    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResults()
    {
    	return $this->results;
    }
    
    /**
     * Add articles
     *
     * @param \Renovate\MainBundle\Entity\Article $articles
     * @return Document
     */
    public function addArticle(\Renovate\MainBundle\Entity\Article $articles)
    {
    	$this->articles[] = $articles;
    
    	return $this;
    }
    
    /**
     * Remove articles
     *
     * @param \Renovate\MainBundle\Entity\Article $articles
     */
    public function removeArticle(\Renovate\MainBundle\Entity\Article $articles)
    {
    	$this->articles->removeElement($articles);
    }
    
    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
    	return $this->articles;
    }
    
    protected function getUploadRootDir()
    {
    	return __DIR__.'/../../../../'.$this->getUploadDir();
    }
    
    protected function getUploadDir()
    {
    	return 'web/bundles/renovate/documents';
    }
    
    public function getAbsolutePath()
    {
    	return null === $this->path
    	? null
    	: $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath()
    {
    	return null === $this->path
    	? null
    	: $this->getUploadDir().'/'.$this->path;
    }
    
    public function upload()
    {
    	if (null === $this->getFile()) return false;
    	 
    	$pathinfo = pathinfo($this->getName());
    	 
    	if (in_array($pathinfo['extension'],self::$fileTypes)) {
    		$this->getFile()->move($this->getUploadRootDir(), $this->getFile()->getClientOriginalName());
    		$this->path = $this->getWebPath();
    		$this->file = null;
    		return true;
    	} else {
    		return false;
    	}
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'userid' => $this->getUserid(),
    			'user' => $this->getUser()->getInArray(),
    			'name' => $this->getName(),
    			'uploaded' => $this->getUploaded()->getTimestamp()*1000,
    			'url' => $this->getPath()
    	);
    }
    
    public static function getToken($timestamp)
    {
    	return md5(self::$SALT . $timestamp);
    }
    
    public static function getAvailableTypesInString()
    {
    	$result = '';
    	foreach (self::$fileTypes as $type)
    	{
    		$result .= "'*.".$type."' ";
    	}
    	
    	return $result;
    }
    
    public static function getAllDocuments($em, $userid = null, $inArray = false)
    {
    	$qb = $em->createQueryBuilder();
    	
    	$qb->select('d')
    	   ->from('RenovateMainBundle:Document', 'd')
		   ->orderBy('d.uploaded', 'DESC');
		   
    	if ($userid != null)
    	{
    		$qb->andWhere('d.userid = :userid')
    		->setParameter('userid', $userid);
    	}
    	 
    	$documents = $qb->getQuery()->getResult();
    	if ($inArray)
    	{
    		return array_map(function($document){
    			return $document->getInArray();
    		}, $documents);
    	}else return $documents;
    }
}
