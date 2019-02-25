<?php

namespace Platform\PlatformBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Antiflood extends constraint{

    public $message = "You sending a lot of message, please take a rest and continue with us :)";

    public function validateBy(){
        return 'platform_platform_antiflood';
    }

}

?>