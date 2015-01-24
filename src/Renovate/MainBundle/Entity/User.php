<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Join;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Renovate\MainBundle\Entity\UserRepository")
 */
class User implements UserInterface,\Serializable
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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="patronymic", type="string", length=255)
     */
    private $patronymic;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mobilephone", type="string", length=255)
     */
    private $mobilephone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered", type="datetime")
     */
    private $registered;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @var array
     */
	private $roles;
	
	/**
	 * @ORM\OneToMany(targetEntity="Document", mappedBy="user")
	 * @var array
	 */
	private $documents;
	
	/**
	 * @ORM\OneToMany(targetEntity="Job", mappedBy="user")
	 * @var array
	 */
	private $jobs;
	
	/**
	 * @ORM\OneToMany(targetEntity="News", mappedBy="user")
	 * @var array
	 */
	private $news;
	
	/**
	 * @ORM\OneToMany(targetEntity="Result", mappedBy="user")
	 * @var array
	 */
	private $results;
	
	/**
	 * @ORM\OneToMany(targetEntity="Article", mappedBy="user")
	 * @var array
	 */
	private $articles;
	
	/**
	 * @ORM\OneToMany(targetEntity="Share", mappedBy="user")
	 * @var array
	 */
	private $shares;
    
	public function __construct()
	{
		$this->roles = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set patronymic
     *
     * @param string $patronymic
     * @return User
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * Get patronymic
     *
     * @return string 
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set mobilephone
     *
     * @param string $mobilephone
     * @return User
     */
    public function setMobilephone($mobilephone)
    {
        $this->mobilephone = $mobilephone;

        return $this;
    }

    /**
     * Get mobilephone
     *
     * @return string 
     */
    public function getMobilephone()
    {
        return $this->mobilephone;
    }

    /**
     * Set registered
     *
     * @param \DateTime $registered
     * @return User
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;

        return $this;
    }

    /**
     * Get registered
     *
     * @return \DateTime 
     */
    public function getRegistered()
    {
        return $this->registered;
    }
    
    public function getSalt()
    {
    	return 'jhs%)dif67364b(-=wdfisy@bm3$4$$j&^*&*&%CJH!!@$KJHG';
    }
    
    public function getRoles()
    {
    	return $this->roles->toArray();
    }
    
    public function eraseCredentials()
    {
    	
    }
    
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
    	return serialize(array(
    			$this->id,
    			$this->username,
    			$this->password
    	));
    }
    
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
    	list (
    		$this->id,
    		$this->username,
    		$this->password
    	) = unserialize($serialized);
    }
    

    /**
     * Add roles
     *
     * @param \Renovate\MainBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Renovate\MainBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Renovate\MainBundle\Entity\Role $roles
     */
    public function removeRole(\Renovate\MainBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'username' => $this->getUsername(),
    			'name' => $this->getName(),
    			'surname' => $this->getSurname(),
    			'patronymic' => $this->getPatronymic(),
    			'email' => $this->getEmail(),
    			'mobilephone' => $this->getMobilephone(),
    			'registered' => $this->getRegistered()->getTimestamp()*1000,
    			'roles' => array_map(function($role){return $role->getInArray();}, $this->getRoles())
    	);
    }
    
    public static function getAllUsers($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    	 
    	$qb->select('u')
    	->orderBy('u.registered', 'DESC');
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($user){
    			return $user->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getUsers($em, $offset, $limit, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    
    	$qb->select('u')
    	->orderBy('u.registered', 'DESC')
    	->setFirstResult($offset)
    	->setMaxResults($limit);
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($user){
    			return $user->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getWorkers($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    
    	$qb->select('u')
		   ->join("u.roles", "r")
		   ->where("r.role = 'ROLE_WORKER'")
    	   ->orderBy('u.registered', 'DESC');
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($user){
    			return $user->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getUsersCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u')
    	->select('COUNT(u.id)')
    	->getQuery();
    	 
    	$total = $query->getSingleScalarResult();
    	 
    	return $total;
    }
    
    public static function addUser($em, $ef, $parameters)
    {
    	$user = new User();
    	$user->setUsername($parameters->username);
    	
    	$encoder = $ef->getEncoder($user);
    	$password = $encoder->encodePassword($parameters->password,$user->getSalt());
    	$user->setPassword($password);
    	
    	$user->setName($parameters->name);
    	$user->setSurname($parameters->surname);
    	$user->setPatronymic($parameters->patronymic);
    	$user->setEmail($parameters->email);
    	$user->setMobilephone($parameters->mobilephone);
    	$user->setRegistered(new \DateTime());
    	 
    	foreach($parameters->roles as $role_id){
    		$role = $em->getRepository("RenovateMainBundle:Role")->find($role_id);
    		$role->addUser($user);
    		$user->addRole($role);
    		$em->persist($role);
    	}
    	
    	$em->persist($user);
    	$em->flush();
    	 
    	return $user;
    }
    
    public static function removeUserById($em, $id)
    {	
    	$user = $em->getRepository("RenovateMainBundle:User")->find($id);
    	foreach($user->getRoles() as $role){
    		$user->removeRole($role);
    	}
    	$em->persist($user);
    	$em->flush();
    	$em->remove($user);
    	$em->flush();
    	
    	return true;
    }
    
    public static function editUserById($em, $ef, $id, $parameters)
    {
    	$user = $em->getRepository("RenovateMainBundle:User")->find($id);
    	 
    	$user->setUsername($parameters->username);
  
    	if (isset($parameters->password)){
    		$encoder = $ef->getEncoder($user);
    		$password = $encoder->encodePassword($parameters->password,$user->getSalt());
    		$user->setPassword($password);
    	}
    	
    	$user->setName($parameters->name);
    	$user->setSurname($parameters->surname);
    	$user->setPatronymic($parameters->patronymic);
    	$user->setEmail($parameters->email);
    	$user->setMobilephone($parameters->mobilephone);
    	 
    	$em->persist($user);
    	$em->flush();
    	 
    	foreach($user->getRoles() as $role){
    		$user->removeRole($role);
    	}
    	$em->persist($user);
    	$em->flush();
    	
    	foreach($parameters->roles as $role_id){
    		$role = $em->getRepository("RenovateMainBundle:Role")->find($role_id);
    		$role->addUser($user);
    		$user->addRole($role);
    		$em->persist($role);
    	}
    	$em->persist($user);
    	$em->flush();
    	
    	return $user;
    }

    /**
     * Add documents
     *
     * @param \Renovate\MainBundle\Entity\Document $documents
     * @return User
     */
    public function addDocument(\Renovate\MainBundle\Entity\Document $documents)
    {
        $this->documents[] = $documents;

        return $this;
    }

    /**
     * Remove documents
     *
     * @param \Renovate\MainBundle\Entity\Document $documents
     */
    public function removeDocument(\Renovate\MainBundle\Entity\Document $documents)
    {
        $this->documents->removeElement($documents);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add jobs
     *
     * @param \Renovate\MainBundle\Entity\Job $jobs
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * Add shares
     *
     * @param \Renovate\MainBundle\Entity\Share $shares
     * @return User
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
}
