<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Renovate\MainBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class IndexController extends Controller
{
    public function indexAction(Request $request)
    {
    	//$user = new User();
    	//$encoder = $this->get('security.encoder_factory')->getEncoder($user);
    	//$p = $encoder->encodePassword('12345','jhs%)dif67364b(-=wdfisy@bm3$4$$j&^*&*&%CJH!!@$KJHG');
		
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
    	 
        return $this->render('RenovateMainBundle:Index:index.html.twig', array(
        		'last_username' => $lastUsername,
        		'error' => $error
        ));
    }
}
