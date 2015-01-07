<?php

namespace Ordis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactsController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('OrdisMainBundle:Contacts:index.html.twig');
    }
    
    public function contactUsNgAction(Request $request)
    {
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$message = \Swift_Message::newInstance()
		    	->setSubject($parameters->topic)
		    	->setFrom('dj.slyusar@gmail.com')
		    	->setTo('dj.slyusar@rambler.ru')
		    	->setBody(
    			"П.І.П.: ".$parameters->pip."\n".
    			"Email: ".$parameters->email."\n\n".
    			$parameters->text);
    	
    	$this->get('mailer')->send($message);
    	
    	$response = new Response(json_encode(array("result" => true)));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
}
