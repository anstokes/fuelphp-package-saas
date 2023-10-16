<?php

namespace Anstech\Saas\Interfaces;

interface Validator
{
    // Get error(s) parsing/validating token
    public function getErrors();

    // Validate the token
    public function validateToken(string $token);
}
