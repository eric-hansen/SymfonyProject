<?php

namespace AnzenSolutions\Bundle\RethinkdbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AnzenSolutionsRethinkdbBundle:Default:index.html.twig', array('name' => $name));
    }
}
