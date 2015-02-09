<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Tariff;

class TariffsController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Tariffs:index.html.twig');
    }
    
    public function showPanelAction()
    {
    	return $this->render('RenovateMainBundle:Tariffs:showPanel.html.twig');
    }
    
    public function getTariffsPublicNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Tariff::getTariffsPublic($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function addTariffPublicNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	 
    	$tariff = Tariff::addTariffPublic($em, $this->getUser(), $parameters);
    	 
    	$response = new Response(json_encode(array("result" => $tariff->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
}
