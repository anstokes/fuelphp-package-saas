<?php

namespace Anstech\Saas\Providers\Receiver;
use Anstech\Saas\Interfaces\Receiver;

class Request extends Base implements Receiver
{
    public function getToken() {
        return isset($_GET[$this->name])
            ? $_GET[$this->name]
            : null;
    }

    public function getRedirect()
    {
        return isset($_GET[$this->redirect])
            ? $_GET[$this->redirect]
            : null;
    }
}