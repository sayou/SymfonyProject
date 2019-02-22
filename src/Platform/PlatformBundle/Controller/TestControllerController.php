<?php

namespace Platform\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestControllerController extends Controller
{

    public function indexAction()
    {
        return $this->render('PlatformPlatformBundle:TestController:index.html.twig', array(
            // ...
        ));
    }

}
