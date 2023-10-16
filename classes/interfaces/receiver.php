<?php

namespace Anstech\Saas\Interfaces;

interface Receiver
{
    // Read the JWT from the incoming request
    public function getToken();

    // Read the redirect from the incoming request
    public function getRedirect();
}
