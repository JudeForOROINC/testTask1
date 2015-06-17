<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MagecoreTestTaskBundle:Default:index.html.twig', array('name' => $name));
    }
}
