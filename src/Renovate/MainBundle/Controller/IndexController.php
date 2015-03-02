<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Renovate\MainBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class IndexController extends Controller
{
    public function indexAction(Request $request)
    {
    	$session = $request->getSession();
    	 
    	//get the login error if there is one
    	if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
    		$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    	}elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR))
    	{
    		$error = $session->get(SecurityContext::AUTHENTICATION_ERROR)->getMessage();
    		$session->remove(SecurityContext::AUTHENTICATION_ERROR);
    	}else {
    		$error = '';
    	}
    	 
    	//last username entered by the user
    	$lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
    	
    	$parameters = array(
    			'last_username' => $lastUsername,
        		'error' => $error);
    	
    	$parameters['page'] = $this->get('renovate.pages')->getPageForUrl($this->getRequest()->getUri());
    	
        return $this->render('RenovateMainBundle:Index:index.html.twig', $parameters);
    }
}
