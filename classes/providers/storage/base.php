<?php

namespace Anstech\Saas\Providers\Storage;
use Anstech\Saas\Interfaces\Storage;

abstract class Base implements Storage
{
    // Default name of variable, overrideable via constructor
    protected $name = 'token';

    // Default name of redirect variable, overrideable via constructor
    protected $redirect = 'redirectUri';

    // Checks if name provider, and sets during initialision
    public function __construct(Array $options = []) {
        if (isset($options['name']) && ($name = $options['name'])) {
            $this->name = $name;
        }

        if (isset($options['redirect']) && ($redirect = $options['redirect'])) {
            $this->redirect = $redirect;
        }
    }
}