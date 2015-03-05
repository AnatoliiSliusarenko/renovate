<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Renovate\MainBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Renovate\MainBundle\Entity\News;
use Renovate\MainBundle\Entity\Job;
use Renovate\MainBundle\Entity\Result;
use Renovate\MainBundle\Entity\Article;
use Renovate\MainBundle\Entity\Share;
use Renovate\MainBundle\Entity\Vacancy;

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
    	
    	$em = $this->getDoctrine()->getManager();
    	$parameters['lastNews'] = News::getNews($em, array('offset' => 0,'limit' => 6));
    	$parameters['jobs'] = Job::getJobs($em, array('onhomepage' => 1));
    	$parameters['results'] = Result::getResults($em, array('onhomepage' => 1));
    	$parameters['news'] = News::getNews($em, array('onhomepage' => 1));
    	$parameters['articles'] = Article::getArticles($em, array('onhomepage' => 1));
    	$parameters['shares'] = Share::getShares($em, array('onhomepage' => 1));
    	$parameters['vacancies'] = Vacancy::getVacancies($em, array('onhomepage' => 1));
    	
        return $this->render('RenovateMainBundle:Index:index.html.twig', $parameters);
    }
}
