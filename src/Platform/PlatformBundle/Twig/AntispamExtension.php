<?php

namespace Platform\PlatformBundle\Twig;

use Platform\PlatformBundle\Antispam\PlatformAntispam;

class AntispamExtension extends \Twig_Extension{

    /**
     * @var PlatformAntiSpam
     */
    private $antiSpam;

    public function __construct(PlatformAntispam $antiSpam){
        $this->antiSpam = $antiSpam;
    }

    public function checkIfIsSpam($text){
        return $this->antiSpam->isSpam($text);
    }




    //function of mother class
    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('checkIsSpam',array($this,'checkIfIsSpam'))
        );
    }

    public function getName(){
        return 'PlatformAntispam';
    }

}

?>