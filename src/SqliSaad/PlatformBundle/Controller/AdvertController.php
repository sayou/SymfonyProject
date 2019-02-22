<?php

namespace SqliSaad\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller {

    public function indexAction(){
        return new Response('Hello world from advert controller');
    }

    public function viewAction($id){
        $url = $this->get('router')->generate(
            'hello_id',
            array('id'=>$id)
        );
        return new Response("Your ID is : $id and the URL is : $url");
    }

    public function addAction(){

    }

    public function slugAction($year,$slug,$_format){
        return new Response("Youre slug : $slug was created on $year with format : $_format");
    }

    public function testAction(){
        return new Response('Hello test');
    }

    public function blablaAction(){
        return new Response('Bla Bla route');
    }
}

?>