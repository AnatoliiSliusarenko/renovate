<?php

namespace Ordis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ordis\MainBundle\Entity\User;

class IndexController extends Controller
{
    public function indexAction()
    {
    	//$user = new User();
    	//$encoder = $this->get('security.encoder_factory')->getEncoder($user);
    	//$p = $encoder->encodePassword('12345','jhs%)dif67364b(-=wdfisy@bm3$4$$j&^*&*&%CJH!!@$KJHG');

    	
        return $this->render('OrdisMainBundle:Index:index.html.twig');
    }
}
