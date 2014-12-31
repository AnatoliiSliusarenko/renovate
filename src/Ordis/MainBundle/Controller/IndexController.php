<?php

namespace Ordis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrdisMainBundle:Index:index.html.twig');
    }
}
