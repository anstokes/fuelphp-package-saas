<?php

namespace Anstech\Saas\Providers\Storage;
use Anstech\Saas\Interfaces\Storage;

class Cookie extends Base implements Storage
{
    public function getToken() {
        return isset($_COOKIE[$this->name])
            ? $_COOKIE[$this->name]
            : null;
    }

    public function removeToken() {
        unset($_COOKIE[$this->name]);
        setcookie($this->name, '', 0, '/');
    }

    public function setToken(string $token) {
        $_COOKIE[$this->name] = $token;
        setcookie($this->name, $token, time() + 3600, '/');
    }

    public function getRedirect()
    {
        return isset($_COOKIE[$this->redirect])
            ? $_COOKIE[$this->redirect]
            : null;
    }

    public function setRedirect(string $redirectUri)
    {
        $_COOKIE[$this->redirect] = $redirectUri;
        setcookie($this->redirect, $redirectUri, 0, '/');
    }

    public function removeRedirect()
    {
        unset($_COOKIE[$this->redirect]);
        setcookie($this->redirect, '', 0, '/');
    }
}