<?php

namespace SqliSaad\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SqliSaadPlatformBundle:Default:index.html.twig');
    }
}
