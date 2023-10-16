<?php

namespace Anstech\Saas\Providers\Receiver;
use Anstech\Saas\Interfaces\Receiver;

class Request extends Base implements Receiver
{
    public function getToken() {
        return isset($_POST[$this->name])
            ? $_POST[$this->name]
            : null;
    }

    public function getRedirect()
    {
        return isset($_POST[$this->redirect])
            ? $_POST[$this->redirect]
            : null;
    }
}