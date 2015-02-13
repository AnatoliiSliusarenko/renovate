<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\PageDescription;

class PageDescriptionsController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:PageDescriptions:index.html.twig');
    }
    
    public function getPageDescriptionsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => PageDescription::getPageDescriptions($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getPageDescriptionsCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => PageDescription::getPageDescriptionsCount($em))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function addPageDescriptionNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	 
    	$pageDescription = PageDescription::addPageDescription($em, $parameters);
    	 
    	$response = new Response(json_encode(array("result" => $pageDescription->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    
    public function removePageDescriptionNgAction($page_description_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => PageDescription::removePageDescriptionById($em, $page_description_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function editPageDescriptionNgAction($page_description_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	 
    	$page_description = PageDescription::editPageDescriptionById($em, $page_description_id, $parameters);
    	 
    	$response = new Response(json_encode(array("result" => $page_description->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
