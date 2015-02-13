<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageDescription
 *
 * @ORM\Table(name="page_descriptions")
 * @ORM\Entity
 */
class PageDescription
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;
    
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
     * Set url
     *
     * @param string $url
     * @return PageDescription
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return PageDescription
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
    			'url' => $this->getUrl(),
    			'description' => $this->getDescription()
    	);
    }
    
    public static function getAllPageDescriptions($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:PageDescription")
    	->createQueryBuilder('p');
    	 
    	$qb->select('p');
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($pageDescription){
    			return $pageDescription->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getPageDescriptions($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:PageDescription")
    	->createQueryBuilder('p');
    
    	$qb->select('p');
    
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($pageDescription){
    			return $pageDescription->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getPageDescriptionsCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:PageDescription")
    	->createQueryBuilder('p')
    	->select('COUNT(p.id)')
    	->getQuery();
    	 
    	$total = $query->getSingleScalarResult();
    	 
    	return $total;
    }
    
    public static function addPageDescription($em, $parameters)
    {
    	$pageDescription = new PageDescription();
    	$pageDescription->setUrl($parameters->url);
    	$pageDescription->setDescription($parameters->description);
    	 
    	$em->persist($pageDescription);
    	$em->flush();
    	 
    	return $pageDescription;
    }
    
    public static function removePageDescriptionById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    
    	$qb->delete('RenovateMainBundle:PageDescription', 'p')
    	->where('p.id = :id')
    	->setParameter('id', $id);
    
    	return $qb->getQuery()->getResult();
    }
    
    public static function editPageDescriptionById($em, $id, $parameters)
    {
    	$pageDescription = $em->getRepository("RenovateMainBundle:PageDescription")->find($id);
    	 
    	$pageDescription->setUrl($parameters->url);
    	$pageDescription->setDescription($parameters->description);
    	 
    	$em->persist($pageDescription);
    	$em->flush();
    	 
    	return $pageDescription;
    }
    
    public static function findPageDescriptionByUrl($em, $url)
    {
    	$qb = $em->getRepository("RenovateMainBundle:PageDescription")
    	->createQueryBuilder('p');
    	
    	$qb->select('p')
    	->where('p.url = :url')
    	->setParameter('url', $url);
    	
    	$result = $qb->getQuery()->getResult();
    	return ($result != null ) ? $result[0]->getDescription() : null ;
    }
}
