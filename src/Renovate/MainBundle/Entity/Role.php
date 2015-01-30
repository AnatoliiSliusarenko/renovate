<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Role
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Role implements RoleInterface
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
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     * @var array
     */
	private $users;
    
	public function __construct()
	{
		$this->users = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Role
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
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Role
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
     * Add users
     *
     * @param \Renovate\MainBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\Renovate\MainBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Renovate\MainBundle\Entity\User $users
     */
    public function removeUser(\Renovate\MainBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'name' => $this->getName(),
    			'role' => $this->getRole(),
    			'description' => $this->getDescription()
    	);
    }
    
    public static function getRoles($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Role")
    	->createQueryBuilder('r');
    
    	$qb->select('r');
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($role){
    			return $role->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getClientRoles($em, $inArray = false)
    {
    	$clientRoles = array('ROLE_CLIENT_OSBB','ROLE_CLIENT_BUILDING','ROLE_CLIENT_PRIVATE_HOUSE','ROLE_CLIENT_PRIVATE_INSTITUTION','ROLE_CLIENT_STATE_INSTITUTION');
    	
    	$qb = $em->getRepository("RenovateMainBundle:Role")
    	->createQueryBuilder('r');
    
    	$qb->select('r')
    	   ->where($qb->expr()->in('r.role', $clientRoles));
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($role){
    			return $role->getInArray();
    		}, $result);
    	}else return $result;
    }
}
