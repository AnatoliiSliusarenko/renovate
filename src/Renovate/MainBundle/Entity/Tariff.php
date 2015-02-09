<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tariff
 *
 * @ORM\Table(name="tariffs")
 * @ORM\Entity
 */
class Tariff
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
     * @ORM\Column(name="parentid", type="integer")
     */
    private $parentid;

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
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float")
     */
    private $discount;

    /**
     * @var float
     *
     * @ORM\Column(name="payment", type="float")
     */
    private $payment;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="squaring", type="integer")
     */
    private $squaring;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activated", type="datetime")
     */
    private $activated;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tariffs")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
    /**
     * @ORM\OneToMany(targetEntity="TariffService", mappedBy="tariff")
     * @var array
     */
    private $tariffServices;
    
    private function createTariffServices($em, $parameters){
    	
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
     * Set parentid
     *
     * @param integer $parentid
     * @return Tariff
     */
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;

        return $this;
    }

    /**
     * Get parentid
     *
     * @return integer 
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Tariff
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
     * @return Tariff
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
     * Set active
     *
     * @param boolean $active
     * @return Tariff
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set discount
     *
     * @param float $discount
     * @return Tariff
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float 
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set payment
     *
     * @param float $payment
     * @return Tariff
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return float 
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Tariff
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
     * Set squaring
     *
     * @param integer $squaring
     * @return Tariff
     */
    public function setSquaring($squaring)
    {
        $this->squaring = $squaring;

        return $this;
    }

    /**
     * Get squaring
     *
     * @return integer 
     */
    public function getSquaring()
    {
        return $this->squaring;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Tariff
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
     * Set activated
     *
     * @param \DateTime $activated
     * @return Tariff
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Get activated
     *
     * @return \DateTime 
     */
    public function getActivated()
    {
        return $this->activated;
    }
    
    /**
     * Set user
     *
     * @param \Renovate\MainBundle\Entity\User $user
     * @return Tariff
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
     * Add tariffServices
     *
     * @param \Renovate\MainBundle\Entity\TariffService $tariffServices
     * @return Tariff
     */
    public function addTariffService(\Renovate\MainBundle\Entity\TariffService $tariffServices)
    {
    	$this->tariffServices[] = $tariffServices;
    
    	return $this;
    }
    
    /**
     * Remove tariffServices
     *
     * @param \Renovate\MainBundle\Entity\TariffService $tariffServices
     */
    public function removeTariffService(\Renovate\MainBundle\Entity\TariffService $tariffServices)
    {
    	$this->tariffServices->removeElement($tariffServices);
    }
    
    /**
     * Get tariffServices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTariffServices()
    {
    	return $this->tariffServices;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->tariffServices = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'parentid' => $this->getParentid(),
    			'userid' => $this->getUserid(),
    			'name' => $this->getName(),
    			'active' => $this->getActive(),
    			'discount' => $this->getDiscount(),
    			'payment' => $this->getPayment(),
    			'price' => $this->getPrice(),
    			'squaring' => $this->getSquaring(),
    			'activated' => $this->getActivated()->getTimestamp()*1000,
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'user' => $this->getUser()->getInArray()
    	);
    }
    
    public static function getTariffsPublic($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Tariff")
    	->createQueryBuilder('t');
    
    	$qb->select('t')
    	->orderBy('t.created', 'DESC')
    	->where('t.parentid is NULL');
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($tariff){
    			return $tariff->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function addTariffPublic($em, \Renovate\MainBundle\Entity\User $user, $parameters)
    {
    	$em->getConnection()->beginTransaction();
    	try {
    		//$category = $em->getRepository("RenovateMainBundle:ServiceCategory")->find($parameters->categoryid);
    		 
    		$tariff = new Tariff();
    		$tariff->setUserid($user->getId());
    		$tariff->setUser($user);
    		$tariff->setName($parameters->name);
    		$tariff->setActive(TRUE);
    		$tariff->setDiscount($parameters->discount);
    		
    		$tariff->setPayment(1);///!!!
    		$tariff->setPrice(1);////!!!
    		
    		$tariff->setSquaring(1);
    		$tariff->setCreated(new \DateTime());
    		$tariff->setActivated(new \DateTime());
    		
    		$em->persist($tariff);
    		$em->flush();
    		 
    		$tariff->createTariffServices($em, $parameters);
    
    		$em->getConnection()->commit();
    		return $tariff;
    	} catch(Exception $e) {
    		$em->getConnection()->rollback();
    		throw $e;
    	}
    }
}
