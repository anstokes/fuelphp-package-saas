<?php

namespace Anstech\Saas\Providers\Validator;
use Anstech\Saas\Interfaces\Validator;

// External classes
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Signer\Hmac;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator as JWTValidator;

class JWT implements Validator
{
    protected $errors = [];

    protected $secret;

    public function __construct(Array $options = []) {
        $this->secret = isset($options['secret'])
            ? $options['secret']
            : null;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function validateToken(string|null $token)
    {
        if (! $this->secret) {
            $this->errors[] = 'No secret provider';
            return null;
        }
        if (! $token) {
            $this->errors[] = 'No token provided';
            return null;
        }

        $parser = new Parser(new JoseEncoder());

        // Attempt to parse the JWT token
        try {
            $token = $parser->parse($token);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            // echo 'Error parsing JWT token: ' . $e->getMessage();
            $this->errors[] = $e->getMessage();
            return null;
        }

        // Validate the JWT token
        $validator = new JWTValidator();
        if (
            !$validator->validate(
                $token,
                new SignedWith(
                    new Hmac\Sha256(),
                    InMemory::plainText($this->secret)
                )
            )
        ) {
            // echo 'Invalid JWT token';
            $this->errors[] = 'JWT token failed validation';
            return null;
        }

        return $token;
    }
}
