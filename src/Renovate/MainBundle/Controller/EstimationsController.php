<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Estimation;

class EstimationsController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Estimations:index.html.twig');
    }
    
    public function getEstimationsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Estimation::getEstimations($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getEstimationsCountNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Estimation::getEstimationsCount($em, $request->query->all()))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
