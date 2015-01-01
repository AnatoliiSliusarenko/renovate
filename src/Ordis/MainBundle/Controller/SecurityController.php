<?php

namespace Ordis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
    	
    	//get the login error if there is one
    	if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
    		$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    	}elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR))
    	{
    		$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
    		$session->remove(SecurityContext::AUTHENTICATION_ERROR);
    	}else {
    		$error = '';
    	}
    	
    	//last username entered by the user
    	$lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
    	
        return new Response(json_encode(array(
        		'last_username' => $lastUsername,
        		'error' => $error->getMessage()
        )));
    }
}
