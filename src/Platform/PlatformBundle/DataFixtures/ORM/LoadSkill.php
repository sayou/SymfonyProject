<?php

namespace Platform\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Platform\PlatformBundle\Entity\Skill;

class LoadSkill implements FixtureInterface{

    public function load(ObjectManager $manager){

        $names = array(
            "PHP",
            "SYMFONY",
            "c++",
            "JAVA",
            ".NET"
        );

        foreach($names as $name){
            $skill = new Skill();
            $skill->setName($name);

            $manager->persist($skill);
        }

        $manager->flush();
    }
}

?>