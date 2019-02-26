<?php

namespace User\OCUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OCUserBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
