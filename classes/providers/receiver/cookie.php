<?php

namespace Anstech\Saas\Providers\Receiver;
use Anstech\Saas\Interfaces\Receiver;

class Request extends Base implements Receiver
{
    public function getToken() {
        return isset($_COOKIE[$this->name])
            ? $_COOKIE[$this->name]
            : null;
    }

    public function getRedirect()
    {
        return isset($_COOKIE[$this->redirect])
            ? $_COOKIE[$this->redirect]
            : null;
    }
}