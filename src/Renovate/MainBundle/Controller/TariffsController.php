<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TariffsController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Tariffs:index.html.twig');
    }
}
