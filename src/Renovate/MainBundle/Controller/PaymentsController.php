<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Payment;

class PaymentsController extends Controller
{
    
    public function getUserPaymentsNgAction($user_id, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Payment::getUserPayments($em, $user_id, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getUserPaymentsCountNgAction($user_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Payment::getUserPaymentsCount($em, $user_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function getMyPaymentsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Payment::getUserPayments($em, $this->getUser()->getId(), $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getMyPaymentsCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Payment::getUserPaymentsCount($em, $this->getUser()->getId()))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
