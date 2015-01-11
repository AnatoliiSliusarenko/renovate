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
    
    public function showJobAction($job_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$job = $em->getRepository("RenovateMainBundle:Job")->find($job_id);
    	return $this->render('RenovateMainBundle:Jobs:showJob.html.twig', array("job" => $job));
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
    
    
    public function addJobNgAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw $this->createAccessDeniedException();
    	}
    	
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$job = Job::addJob($em, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $job->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function removeJobNgAction($job_id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw $this->createAccessDeniedException();
    	}
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Job::removeJobById($em, $job_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function editJobNgAction($job_id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw $this->createAccessDeniedException();
    	}
    	
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$job = Job::editJobById($em, $job_id, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $job->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
