<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VacanciesController extends Controller
{
    public function indexAction()
    {
    	$parameters = array();
    	$parameters['pageDescription'] = $this->get('renovate.seo')->getDescriptionForUrl($this->getRequest()->getUri());
    	
    	return $this->render('RenovateMainBundle:Vacancies:index.html.twig', $parameters);
    }
}
