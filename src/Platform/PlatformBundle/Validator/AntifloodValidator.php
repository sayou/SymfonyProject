<?php

namespace Platform\PlatformBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntifloodValidator extends ConstraintValidator{

    private $requestStack;
    private $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em){
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint){

        $request = $this->requestStack->getCurrentRequest();

        $ip = $request->getClientIp();

        $isFlood = $this->em
            ->getRepository('PlatformPlatformBundle:Advert')
            ->isFlood($ip,15);

        if($isFlood){
            $this->context
                ->addViolation($constraint->message);
        }
        // if(strlen($value) < 3){
        //     $this
        //         ->context
        //             ->buildViolation($constraint->message)
        //             ->setParameters(array('%string%' => $value))
        //             ->addViolation();
        // }
    }
}

?>