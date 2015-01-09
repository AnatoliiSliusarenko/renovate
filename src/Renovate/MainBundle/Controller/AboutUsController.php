<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutUsController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:AboutUs:index.html.twig');
    }
}
