<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Job;

class JobsController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Jobs:index.html.twig');
    }
    
    public function getJobsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$offset = ($request->query->get('offset')) ? $request->query->get('offset') : 0 ;
    	$limit = ($request->query->get('limit')) ? $request->query->get('limit') : 20 ;
    	
    	$response = new Response(json_encode(array("result" => Job::getJobs($em, $offset, $limit, true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getJobsCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Job::getJobsCount($em))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
}
