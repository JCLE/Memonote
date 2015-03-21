<?php

namespace JCLE\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JCLEUserBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
}
