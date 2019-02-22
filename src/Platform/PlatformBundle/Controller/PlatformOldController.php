<?php

namespace Platform\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlatformController extends Controller{

    public function indexAction($page, Request $request){
        /*$tag = $request->query->get('tag');

        return $this->render(
            'PlatformPlatformBundle:Platform:index.html.twig',
            array(
                'id' => $page,
                'tag' => $tag
                )
        );*/

        /*$response = new Response(json_encode(array('id'=>$page)));
        $response->headers->set('Content-type','application/json');

        return $response; ==============> return new JsonResponse(array('id'=>$page));*/

        /*$session = $request->getSession();
        $userId = $session->get('user_id');

        $session->set('user_id',10);

        return new Response('Your session id '.$userId);*/

        return $this->render('PlatformPlatformBundle:Platform:index.html.twig', array(
            'id' => $page
          ));
    }

    public function annonceAction($id,Request $request){
        //$url = $this->get('router')->generate('platform_platform_homepage');

        //return new RedirectResponse($url);

        //return $this->redirect($url);

        $session = $request->getSession();

        $session->getFlashBag()->add('info','Annonce bien enregistrée');
        $session->getFlashBag()->add('info','Wa rah golnalak tzadat');

        return $this->redirectToRoute('platform_platform_homepage',array('page'=>5));
    }
}


?>