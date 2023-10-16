<?php

namespace Anstech\Saas\Providers\Storage;
use Anstech\Saas\Interfaces\Storage;

class Session extends Base implements Storage
{
    public function __construct($name) {
        // Start the session, if it not already
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        parent::__construct($name);
    }

    public function getToken() {
        return isset($_SESSION[$this->name])
            ? $_SESSION[$this->name]
            : null;
    }
    
    public function removeToken() {
        unset($_SESSION[$this->name]);
    }

    public function setToken(string $token) {
        $_SESSION[$this->name] = $token;
    }

    public function getRedirect()
    {
        return isset($_SESSION[$this->redirect])
            ? $_SESSION[$this->redirect]
            : null;
    }

    public function setRedirect(string $redirectUri)
    {
        $_SESSION[$this->redirect] = $redirectUri;
    }

    public function removeRedirect()
    {
        unset($_SESSION[$this->redirect]);
    }
}
