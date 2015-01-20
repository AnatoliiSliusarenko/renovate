<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Result;
use Renovate\MainBundle\Entity\Document;

class ResultsController extends Controller
{
    public function indexAction()
    {
    	$timestamp = time();
    	$token = Document::getToken($timestamp);
    	return $this->render('RenovateMainBundle:Results:index.html.twig', array(
    			'timestamp' => $timestamp,
    			'token' => $token
    	));
    }
    
    public function showResultAction($result_name_translit)
    {
    	$em = $this->getDoctrine()->getManager();
    	$result = $em->getRepository("RenovateMainBundle:Result")->findByNameTranslit($result_name_translit);
    	return $this->render('RenovateMainBundle:Results:showResult.html.twig', array("result" => $result[0]));
    }
    
    public function getResultsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Result::getResults($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getResultsCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Result::getResultsCount($em))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function addResultNgAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') && false === $this->get('security.context')->isGranted('ROLE_EDITOR')) {
    		throw $this->createAccessDeniedException();
    	}
    	
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$transliterater = $this->get('renovate.transliterater');
    	$result = Result::addResult($em, $transliterater, $this->getUser(), $parameters);
    	
    	$response = new Response(json_encode(array("result" => $result->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function removeResultNgAction($result_id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') && false === $this->get('security.context')->isGranted('ROLE_EDITOR')) {
    		throw $this->createAccessDeniedException();
    	}
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Result::removeResultById($em, $result_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function editResultNgAction($result_id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') && false === $this->get('security.context')->isGranted('ROLE_EDITOR')) {
    		throw $this->createAccessDeniedException();
    	}
    	
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$transliterater = $this->get('renovate.transliterater');
    	$result = Result::editResultById($em, $transliterater, $result_id, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $result->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
