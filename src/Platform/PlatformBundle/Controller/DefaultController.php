<?php

namespace Platform\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PlatformPlatformBundle:Default:index.html.twig');
    }
}
