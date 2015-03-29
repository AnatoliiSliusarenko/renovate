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
    
}
