<?php

namespace Anstech\Saas\Interfaces;

interface Storage
{
    // Retrieve the JWT from the store
    public function getToken();

    // Remove the JWT from the store
    public function removeToken();

    // Set the JWT in the store
    public function setToken(string $token);

    // Get redirect from the store
    public function getRedirect();

    // Set the redirect uri in the store
    public function setRedirect(string $redirectUri);

    // Remove the redirect
    public function removeRedirect();
}
