<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\CostCategory;

class CostCategoriesController extends Controller
{
    public function getCostCategoriesNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => CostCategory::getCostCategories($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
