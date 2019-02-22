<?php

namespace Platform\PlatformBundle\Antispam;

class PlatformAntispam{

    private $mailer;
    private $locale;
    private $minLength;

    public function __constructor(\Swift_Mailer $mailer, $locale, $minLength){
        $this->mailer = $mailer;
        $this->locale = $locale; 
        $this->minLength = (int) $minLength;
    }


    public function isSpam($text){
        return strlen($text) < $this->minLength;
    }
}

?>