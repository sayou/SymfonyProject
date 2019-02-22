<?php

namespace Platform\PlatformBundle\Controller;

use Platform\PlatformBundle\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Platform\PlatformBundle\Form\AdvertType;

class PlatformController extends Controller{

    public function indexAction($page){
        if($page < 1){
            throw new NotFoundHttpException("Page $page not found !!");
        }

        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('PlatformPlatformBundle:Advert')
            ->getAdverts($page,2);
        
        $total_page = ceil(count($listAdverts) / 2);
        //$listAdverts->getIterator()->count() nombre date in page
        if($total_page < $page){
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

    public function addAction(Request $request){
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

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();

                $em->persist($advert);

                $em->flush();

                $request->getSession()->getFlashBag()->add('notice','advert saved with success');

                return $this->redirectToRoute('platform_annonce',array('id'=>$advert->getId()));
            }else{
                throw new NotFoundHttpException('Ooops something wrong');
            }
        }

        return $this->render('PlatformPlatformBundle:Platform:add.html.twig',array(
            'form'=>$form->createView()
        ));

    }

    public function editAction($id, Request $request){
        $advert = new Advert();

        $advert = $this->getDoctrine()
            ->getManager()
            ->getRepository('PlatformPlatformBundle:Advert')
            ->find($id);

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $advert);

        $formBuilder
            ->add('date', DateType::class)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('author', TextType::class)
            ->add('published', CheckboxType::class, array('required'=>false))
            ->add('save', SubmitType::class);

        $form = $formBuilder->getForm();

        return $this->render('PlatformPlatformBundle:Platform:edit.html.twig',array(
            'form'=>$form->createView()
        ));
    }

}

?>