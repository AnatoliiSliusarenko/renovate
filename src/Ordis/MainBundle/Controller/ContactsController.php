<?php

namespace Ordis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactsController extends Controller
{
    public function indexAction(Request $request)
    {
    	$parameters = array();
    	if ($request->getMethod() == 'POST')
    	{
    		$message = \Swift_Message::newInstance()
    					->setSubject($request->request->get('topic'))
    					->setFrom('dj.slyusar@gmail.com')
    					->setTo('dj.slyusar@rambler.ru')
    					->setBody(
    					"П.І.П.: ".$request->request->get('pip')."\n".
    					"Email: ".$request->request->get('email')."\n\n".		
    					$request->request->get('message'));
    		
    		$this->get('mailer')->send($message);
    		$parameters['message_result'] = 'Ваш лист успішно надіслано!';
    	}
    	
        return $this->render('OrdisMainBundle:Contacts:index.html.twig',$parameters);
    }
}
