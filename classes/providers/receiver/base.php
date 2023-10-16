<?php

namespace Anstech\Saas\Providers\Receiver;
use Anstech\Saas\Interfaces\Receiver;

abstract class Base implements Receiver
{
    // Default name of variable, overrideable via constructor
    protected $name = 'token';

    // Default name of redirect uri variable, overrideable via constructor
    protected $redirect = 'redirectUri';

    // Checks if name provided, and sets during initialision
    public function __construct(Array $options = []) {
        if (isset($options['name']) && ($name = $options['name'])) {
            $this->name = $name;
        }

        if (isset($options['redirect']) && ($redirectUri = $options['redirect'])) {
            $this->redirect = $redirectUri;
        }
    }
}