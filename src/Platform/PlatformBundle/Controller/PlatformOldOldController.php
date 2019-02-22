<?php

namespace Platform\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Platform\PlatformBundle\Entity\Advert;
use Platform\PlatformBundle\Entity\Image;
use Platform\PlatformBundle\Entity\AdvertSkill;
use Platform\PlatformBundle\Entity\Application;

class PlatformController extends Controller{

    public function indexAction($page){
        
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository("PlatformPlatformBundle:Advert");

        $listAdverts = $repository->myFindAll();

        var_dump($listAdverts);

        /*if($page < 1){
            throw new NotFoundHttpException("Page $page not found");
        }

        return $this->render('PlatformPlatformBundle:Platform:index.html.twig');*/

        /*$mailer = $this->container->get('mailer');
        var_dump($mailer);*/

        /*$antispam = $this->container->get('platform_platform.antispam');
        $text="saad";
        if($antispam->isSpam($text)){
            throw new \Exception('Your message is a spam');
        }*/
       // return $this->render('PlatformPlatformBundle:Platform:index.html.twig');
    }

    public function annonceAction($id){
        
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('PlatformPlatformBundle:Advert')->find($id);

        if(!$advert){
            throw new NotFoundHttpException("L'annonce d'id $id n'existe pas !");
        }

        $listApplications = $em
            ->getRepository('PlatformPlatformBundle:Application')
            ->findBy(array('advert'=>$advert));


        $listAdvertSkill = $em
            ->getRepository('PlatformPlatformBundle:AdvertSkill')
            ->findBy(array('advert'=>$advert));

        
        return $this->render('PlatformPlatformBundle:Platform:annonce.html.twig',array(
            'advert' => $advert,
            'listApplicaitons' => $listApplications,
            'listAdvertSkill' => $listAdvertSkill ?? null
        ));
    }

    public function addAction(Request $request){
        $advert = new Advert();

        $advert->setTitle('Title test');
        $advert->setContent('Just a content test');
        $advert->setAuthor('Saad');

        $application = new Application();
        $application->setAuthor('Test');
        $application->setContent('I\'m motived to this post');

        $image = new Image();
        $image->setUrl('https://cdn.shopify.com/s/files/1/0010/9215/7503/t/10/assets/cbt2/images/result-clipboard.png?6121280604586898815');
        $image->setAlt('Test image');

        $advert->setImage($image);

        $application1 = new Application();
        $application1->setAuthor('just a test');
        $application1->setContent('I\'m not motived to this post');

        $em = $this->getDoctrine()->getManager();

        $application->setAdvert($advert);
        $application1->setAdvert($advert);

        $em->persist($advert);

        $em->persist($application);
        $em->persist($application1);

        $em->flush();

        /*$advert = new Advert();
        $advert->setTitle('title test');
        $advert->setContent('Just a content test');
        $advert->setAuthor('Author');

        $listSkills = $em->getRepository('PlatformPlatformBundle:Skill')->findAll();

        $image = new Image();
        $image->setUrl('https://cdn.shopify.com/s/files/1/0010/9215/7503/t/10/assets/cbt2/images/result-clipboard.png?6121280604586898815');
        $image->setAlt('Test image');

        $advert->setImage($image);

        foreach($listSkills as $skill){
            $advertSkill = new AdvertSkill();

            $advertSkill->setAdvert($advert);
            $advertSkill->setSkill($skill);

            $advertSkill->setLevel('Expert');

            $em->persist($advertSkill);
        }

        $em->persist($advert);

        $em->flush();*/

        // $advert = new Advert();
        // //$advert->setDate(date_create(date('Y-m-h')));
        // $advert->setDate(new \Datetime());
        // $advert->setTitle('The title');
        // $advert->setAuthor('The author');
        // $advert->setContent('The content bla bla bla bla');

        // $image = new Image();
        // $image->setUrl('https://cdn.shopify.com/s/files/1/0010/9215/7503/t/10/assets/cbt2/images/result-clipboard.png?6121280604586898815');
        // $image->setAlt('Test image');

        // $advert->setImage($image);

        // $em = $this->getDoctrine()->getManager();

        // $em->persist($advert);

        // $em->flush();

        
        // if($request->isMethod('POST')){
        //     $request->getSession()->getFlashBag()->add('notice','Coool, added with success');
        //     return $this->redirectToRoute('platform_annonce',array('id'=>$advert->getId()));
        // }
        // return $this->redirectToRoute('PlatformPlatformBundle:Platform:add.html.twig',array('advert'=>$advert));
    }

    public function editAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();

        /*$advert1->setTitle("The new title");
        $advert1->setContent("The new content");*/

        $advert = $em->getRepository('PlatformPlatformBundle:Advert')->find($id);

        if(null === $advert){
            throw new NotFoundHttpException("L'annonce d'id $id n'existe pas");
        }

        /*$listCategories = $em->getRepository('PlatformPlatformBundle:Category')->findAll();

        foreach($listCategories as $category){
            $advert->addCategory($category);
        }*/

        /*$advert1->setDate(new \Datetime());

        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Edited with success');
            return $this->redirectToRoute('platform_annonce',array('id'=>$id));
        }
        return $this->redirectToRoute('PlatformPlatformBundle:Platform:edit.html.twig');*/

        $em->flush();
    }

    public function deleteAction($id){
        //return $this->redirectToRoute('PlatformPlatformBundle:Platform:delete.html.twig');
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('PlatformPlatformBundle:Advert')->find($id);

        if(null === $advert){
            throw new NotFoundHttpException("L'annonce d'id : $id n'existe pas");
        }

        foreach($advert->getCategories() as $category){
            $advert->removeCategory($category);
        }

        $em->flush();
    }

    public function testAction(){
        // $repository = $this
        //     ->getDoctrine()
        //     ->getManager()
        //     ->getRepository('PlatformPlatformBundle:Advert');

        // $getbydateandauthor = $repository->findByAuthorAndDate("Author",new \DateTime());

        // var_dump($getbydateandauthor);

        // $repository = $this
        //     ->getDoctrine()
        //     ->getManager()
        //     ->getRepository('PlatformPlatformBundle:Advert');
        
        // $getfrommyfind = $repository->getById(5);

        // var_dump($getfrommyfind);

        // $repository = $this
        //     ->getDoctrine()
        //     ->getManager()
        //     ->getRepository('PlatformPlatformBundle:Application');

        // /*$getbyidDQL = $repository->myFindDQL(5);*/
        // //$getwithjoin = $repository->getAdvertJoinApplication();
        // //$datas = $repository->getAdvertWithCategories(array('Web Dev'));
        // $datas = $repository->getApplicationsWithAdvert(10);
        // var_dump($datas);

        $advert = new Advert();
        $advert->setTitle("Just a title test to verify slug");
        $advert->setAuthor('Bla BLa');
        $advert->setContent('Bla Bla Bla Bla Bla Bla Bla Bla Bla');

        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);

        $em->flush();

        return new Response('Slug génére : ' . $advert->getSlug());

    }
}

?>