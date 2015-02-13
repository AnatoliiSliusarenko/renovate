<?php
namespace Renovate\MainBundle\Services;

use Doctrine\ORM\Mapping as ORM;
use Renovate\MainBundle\Entity\PageDescription;

class SEO
{
	private $em = null;
	
	public function __construct($em)
	{
		$this->em = $em;
	}
	
    public function getDescriptionForUrl($url)
    {
    	return PageDescription::findPageDescriptionByUrl($this->em, $url);
    }
}