<?php

namespace Ordis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ordis\MainBundle\Entity\Job;

class JobsController extends Controller
{
    public function indexAction()
    {
    	return $this->render('OrdisMainBundle:Jobs:index.html.twig');
    }
    
    public function getNgJobsAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Job::getAllJobs($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
