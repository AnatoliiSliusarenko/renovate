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
     * @ORM\OneToMany(targetEntity="Job", mappedBy="label")
     * @var array
     */
    private $jobsLabels;
    
    /**
     * @ORM\OneToMany(targetEntity="News", mappedBy="document")
     * @var array
     */
    private $news;
    
    /**
     * @ORM\OneToMany(targetEntity="News", mappedBy="label")
     * @var array
     */
    private $newsLabels;
    
    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="document")
     * @var array
     */
    private $results;
    
    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="label")
     * @var array
     */
    private $resultsLabels;
    
    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="document")
     * @var array
     */
    private $articles;
    
    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="label")
     * @var array
     */
    private $articlesLabels;
    
    /**
     * @ORM\OneToMany(targetEntity="Share", mappedBy="document")
     * @var array
     */
    private $shares;
    
    /**
     * @ORM\OneToMany(targetEntity="Share", mappedBy="label")
     * @var array
     */
    private $sharesLabels;
    
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
     * Add jobsLabels
     *
     * @param \Renovate\MainBundle\Entity\Job $jobsLabels
     * @return Document
     */
    public function addJobsLabel(\Renovate\MainBundle\Entity\Job $jobsLabels)
    {
    	$this->jobsLabels[] = $jobsLabels;
    
    	return $this;
    }
    
    /**
     * Remove jobsLabels
     *
     * @param \Renovate\MainBundle\Entity\Job $jobsLabels
     */
    public function removeJobsLabel(\Renovate\MainBundle\Entity\Job $jobsLabels)
    {
    	$this->jobsLabels->removeElement($jobsLabels);
    }
    
    /**
     * Get jobsLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJobsLabels()
    {
    	return $this->jobsLabels;
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
     * Add newsLabels
     *
     * @param \Renovate\MainBundle\Entity\News $newsLabels
     * @return Document
     */
    public function addNewsLabel(\Renovate\MainBundle\Entity\News $newsLabels)
    {
    	$this->newsLabels[] = $newsLabels;
    
    	return $this;
    }
    
    /**
     * Remove newsLabels
     *
     * @param \Renovate\MainBundle\Entity\News $newsLabels
     */
    public function removeNewsLabel(\Renovate\MainBundle\Entity\News $newsLabels)
    {
    	$this->newsLabels->removeElement($newsLabels);
    }
    
    /**
     * Get newsLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNewsLabels()
    {
    	return $this->newsLabels;
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
     * Add resultsLabels
     *
     * @param \Renovate\MainBundle\Entity\Result $resultsLabels
     * @return Document
     */
    public function addResultsLabel(\Renovate\MainBundle\Entity\Result $resultsLabels)
    {
    	$this->resultsLabels[] = $resultsLabels;
    
    	return $this;
    }
    
    /**
     * Remove resultsLabels
     *
     * @param \Renovate\MainBundle\Entity\Result $resultsLabels
     */
    public function removeResultsLabel(\Renovate\MainBundle\Entity\Result $resultsLabels)
    {
    	$this->resultsLabels->removeElement($resultsLabels);
    }
    
    /**
     * Get resultsLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultsLabels()
    {
    	return $this->resultsLabels;
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
    
    /**
     * Add articlesLabels
     *
     * @param \Renovate\MainBundle\Entity\Article $articlesLabels
     * @return Document
     */
    public function addArticlesLabel(\Renovate\MainBundle\Entity\Article $articlesLabels)
    {
    	$this->articlesLabels[] = $articlesLabels;
    
    	return $this;
    }
    
    /**
     * Remove articlesLabels
     *
     * @param \Renovate\MainBundle\Entity\Article $articlesLabels
     */
    public function removeArticlesLabel(\Renovate\MainBundle\Entity\Article $articlesLabels)
    {
    	$this->articlesLabels->removeElement($articlesLabels);
    }
    
    /**
     * Get articlesLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticlesLabels()
    {
    	return $this->articlesLabels;
    }
    
    /**
     * Add shares
     *
     * @param \Renovate\MainBundle\Entity\Share $shares
     * @return Document
     */
    public function addShare(\Renovate\MainBundle\Entity\Share $shares)
    {
    	$this->shares[] = $shares;
    
    	return $this;
    }
    
    /**
     * Remove shares
     *
     * @param \Renovate\MainBundle\Entity\Share $shares
     */
    public function removeShare(\Renovate\MainBundle\Entity\Share $shares)
    {
    	$this->shares->removeElement($shares);
    }
    
    /**
     * Get shares
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShares()
    {
    	return $this->shares;
    }
    
    /**
     * Add sharesLabels
     *
     * @param \Renovate\MainBundle\Entity\Share $sharesLabels
     * @return Document
     */
    public function addSharesLabel(\Renovate\MainBundle\Entity\Share $sharesLabels)
    {
    	$this->sharesLabels[] = $sharesLabels;
    
    	return $this;
    }
    
    /**
     * Remove sharesLabels
     *
     * @param \Renovate\MainBundle\Entity\Share $sharesLabels
     */
    public function removeSharesLabel(\Renovate\MainBundle\Entity\Share $sharesLabels)
    {
    	$this->sharesLabels->removeElement($sharesLabels);
    }
    
    /**
     * Get sharesLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSharesLabels()
    {
    	return $this->sharesLabels;
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
    	 
    	$result = $qb->getQuery()->getResult();
    	if ($inArray)
    	{
    		return array_map(function($document){
    			return $document->getInArray();
    		}, $result);
    	}else return $result;
    }
}
