<?php

namespace Platform\PlatformBundle\Controller;

use Platform\PlatformBundle\Entity\Advert;
use Platform\PlatformBundle\Form\AdvertType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type;
use Platform\PlatformBundle\Form\AdvertEditType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PlatformController extends Controller{

    public function indexAction($page){
        if($page < 1){
            throw new NotFoundHttpException("Page $page not found !!");
        }

        $nbrPerPage = 10;

        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('PlatformPlatformBundle:Advert')
            ->getAdverts($page,$nbrPerPage);
        
        $total_page = ceil(count($listAdverts) / $nbrPerPage);
        
        //$listAdverts->getIterator()->count() nombre date in page
        if($total_page < $page && $total_page != 0){
            throw new NotFoundHttpException("Page $page not found !!");
        }
        // if(!$listAdverts || empty($listAdverts)){
        //     throw new NotFoundHttpException("Page $id not found !");
        // }
        
        return $this->render('PlatformPlatformBundle:Platform:index.html.twig',array(
            'listAdverts' => $listAdverts,
            'total_page' => $total_page,
            'page' => $page
        ));
    }

    public function menuAction($limit){
        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('PlatformPlatformBundle:Advert')
            ->getLastAdvertByLimit($limit);
        
        return $this->render('PlatformPlatformBundle:Platform:menu.html.twig',array(
            'listAdverts' => $listAdverts
        ));
    }

    public function viewAction($id){

        $em = $this->getDoctrine()->getManager();
        $advert = $em
            ->getRepository('PlatformPlatformBundle:Advert')
            ->find($id);

        $advertSkills = $em
            ->getRepository('PlatformPlatformBundle:AdvertSkill')
            ->findBy(array('advert' => $advert));

        if(!$advert){
            throw new NotFoundHttpException("Advert not found !!");
        }
        return $this->render('PlatformPlatformBundle:Platform:view.html.twig', array(
            'advert' => $advert,
            'advertSkills' => $advertSkills ?? null
        ));
    }

    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function addAction(Request $request){

        // if(!$this->get('security.authorization_checker')->isGranted('ROLE_AUTEUR')){
        //     throw new AccessDeniedException('Access autorised for Author !');
        // }
        $em = $this->getDoctrine()->getManager();

       
        //var_dump($advert->getDate());
        //var_dump($advert);
        $advert = new Advert();

        $advert->setAuthor('Saad YOUSFI');


        $form= $this->get('form.factory')->create(AdvertType::class, $advert);

        // $formBuilder
        //     ->add('date', DateType::class)
        //     ->add('title', TextType::class)
        //     ->add('content', TextareaType::class)
        //     ->add('author', TextType::class)
        //     ->add('published', CheckboxType::class, array('required'=>false))
        //     ->add('save', SubmitType::class);

        // $form = $formBuilder->getForm();

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            //$form->handleRequest($request);

            // if($form->isValid()){
            //$advert->getImage()->upload();

            $em = $this->getDoctrine()->getManager();
            
            
            $requestStack = new RequestStack();
            
            $advert->setIp($request->getClientIp());
            $em->persist($advert);
            //$request = $requestStack->getCurrentRequest();
            //var_dump($request);
            
            //$advert->setIp();
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice','advert saved with success');
            return $this->redirectToRoute('platform_annonce',array('id'=>$advert->getId()));
            // }else{
            //     throw new NotFoundHttpException('Ooops something wrong');
            // }
        }

        return $this->render('PlatformPlatformBundle:Platform:add.html.twig',array(
            'form'=>$form->createView(),
            'title' => "Add advert"
        ));

    }

    public function editAction($id, Request $request){
        $advert = new Advert();

        $advert = $this->getDoctrine()
            ->getManager()
            ->getRepository('PlatformPlatformBundle:Advert')
            ->find($id);

        $form= $this->get('form.factory')->create(AdvertEditType::class, $advert);
        // $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $advert);

        // $formBuilder
        //     ->add('date', DateType::class)
        //     ->add('title', TextType::class)
        //     ->add('content', TextareaType::class)
        //     ->add('author', TextType::class)
        //     ->add('published', CheckboxType::class, array('required'=>false))
        //     ->add('save', SubmitType::class);

        // $form = $formBuilder->getForm();

        return $this->render('PlatformPlatformBundle:Platform:add.html.twig',array(
            'form'=>$form->createView(),
            'title' => "Edit advert Number $id"
        ));
    }

    public function deleteAction($id, Request $request){
        
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('PlatformPlatformBundle:Advert')->find($id);
        
        if(null === $advert){
            throw new NotFoundHttpException('Ooops this advert not found');
        }

        $form = $this->get('form.factory')->create();

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info',"The advert deleted with success");

            return $this->redirectToRoute('platform_platform_homepage');
        }

        return $this->render('PlatformPlatformBundle:Platform:delete.html.twig',array(
            'advert' => $advert,
            'form' => $form->createView()
        ));
    }

    public function testAction(){
        $advert = new Advert();

        $advert->setDate(new \DateTime());
        $advert->setTitle('1234567890');
        $advert->setAuthor('As');
        $advert->setContent('g');

        $validator = $this->get('validator');

        $listErrors = $validator->validate($advert);

        if(count($listErrors) > 0){
            return new Response(var_dump($listErrors));
        }else{
            return $this->render("PlatformPlatformBundle:Platform:test.html.twig");
        }
    }

}

?>