<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\User;

class UsersController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Users:index.html.twig');
    }
    
    public function getUsersNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$offset = ($request->query->get('offset')) ? $request->query->get('offset') : 0 ;
    	$limit = ($request->query->get('limit')) ? $request->query->get('limit') : 20 ;
    	
    	$response = new Response(json_encode(array("result" => User::getUsers($em, $offset, $limit, true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getWorkersNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => User::getWorkers($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getClientsNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => User::getClients($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getUsersCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => User::getusersCount($em))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function addUserNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$ef = $this->get('security.encoder_factory');
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$user = User::addUser($em, $ef, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $user->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function removeUserNgAction($user_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => User::removeUserById($em, $user_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function editUserNgAction($user_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$ef = $this->get('security.encoder_factory');
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$user = User::editUserById($em, $ef, $user_id, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $user->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function checkUserTariffNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => $this->getUser()->checkUserTariff($em))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
