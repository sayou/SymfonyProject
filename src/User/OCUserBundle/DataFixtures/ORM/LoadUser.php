<?php

namespace User\OCUserBundle\DataFixtures\ORM;

use User\OCUserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadUser implements FixtureInterface{

    public function load(ObjectManager $manager){
        $listNames = array("Saad","Reda","Anas","Majd");


        foreach($listNames as $name){
            $user = new User();

            $user->setUsername($name);
            $user->setPassword($name);

            $user->setSalt('');

            $user->setRoles(array('ROLE_USER'));

            $manager->persist($user);
        }

        $manager->flush();

    }
}

?>