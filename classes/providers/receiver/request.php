<?php

namespace Anstech\Saas\Providers\Receiver;
use Anstech\Saas\Interfaces\Receiver;

class Request extends Base implements Receiver
{
    public function getToken() {
        return isset($_REQUEST[$this->name])
            ? $_REQUEST[$this->name]
            : null;
    }

    public function getRedirect()
    {
        return isset($_REQUEST[$this->redirect])
            ? $_REQUEST[$this->redirect]
            : null;
    }
}