<?php

namespace Anstech\Saas;

use Anstech\Saas\Interfaces\Receiver;
use Anstech\Saas\Interfaces\Storage;
use Anstech\Saas\Interfaces\Validator;
use Anstech\Saas\Providers\Receiver\Request;
use Anstech\Saas\Providers\Storage\Cookie;
use Anstech\Saas\Providers\Validator\JWT;

class Handler
{
  protected $options = [];

  protected $receiver;

  protected $storage;

  protected $tokenString;

  protected $tokenObject;

  protected $validator;

  public function __construct(
    array $options = [],
    Receiver|string $receiver = Request::class,
    Storage|string $storage = Cookie::class,
    Validator|string $validator = JWT::class,
  ) {
    $this->options = $options;

    $this->receiver = $this->resolveClass($receiver, $this->getOptions('receiver'));

    $this->storage = $this->resolveClass($storage, $this->getOptions('storage'));

    $this->validator = $this->resolveClass($validator, $this->getOptions('validator'));

    // Store the redirect
    if ($redirectUri = $this->receiver->getRedirect()) {
      $this->storage->setRedirect($redirectUri);
    }

    // Get token from request
    $this->tokenString = $this->receiver->getToken();

    if ($this->tokenString) {
      // Store the token for future use
      $this->storage->setToken($this->tokenString);
    } else {
      // Try getting from store
      $this->tokenString = $this->storage->getToken();
    }

    // Validate and return payload
    $this->tokenObject = $this->validator->validateToken($this->tokenString);
  }

  /**
   * Extract relevant options from the options provided to the class
   * or return the provided default, if the key does not exist
   *
   * @param mixed $option
   * @param mixed $default
   *
   * @return mixed
   */
  protected function getOptions($option, $default = [])
  {
    return isset($this->options[$option])
      ? $this->options[$option]
      : $default;
  }

  /**
   * Resolver to create the appropriate classes if the class is provided
   * as a string, rather than an instance
   *
   * @param mixed $class
   * @param mixed $options
   *
   * @return mixed
   */
  protected function resolveClass($class, $options = [])
  {
    if (is_string($class)) {
      // Check that class exists
      if (class_exists($class)) {
        $class = new $class($options);
      } else {
        throw new \Exception("Class not found: {$class}");
      }
    }

    return $class;
  }

  /**
   * Call the provided argument, if it is callable
   * or otherwise redirect to the string
   *
   * @param callable|string|null $arg
   *
   * @return void
   */
  protected function callOrRedirect(callable|string|null $arg)
  {
    // Check if provided option is callable
    if (is_callable($arg)) {
      $arg($this->token());
    } elseif (is_string($arg)) {
      // If string then redirect to target
      header("Location: {$arg}");
      exit(0);
    }
  }

  /**
   * Perform the login redirection, if provided
   *
   * @return void
   */
  public function loginRedirect()
  {
    // Check if a login redirect has been provided
    if (
      isset($this->options['loginRedirect'])
      && ($loginRedirect = $this->options['loginRedirect'])
    ) {
      // Include the redirectUri search param
      if ($redirectUri = $this->storage->getRedirect()) {
        $loginRedirect .= '?redirectUri=' . urlencode($redirectUri);
      }

      // Add call
      $this->callOrRedirect($loginRedirect);
    }

    // Do nothing e.g., to let the parent application handle the redirection
    // if (! $authHandler->hasValidToken()) { do something }
  }

  /**
   * Perform the login redirection, if provided
   *
   * @return void
   */
  public function redirect($route = '')
  {
    // Check if a login redirect has been provided
    if (
      $route
      && isset($this->options[$route])
      && ($loginRedirect = $this->options[$route])
    ) {
      // Include the redirectUri search param
      if ($redirectUri = $this->storage->getRedirect()) {
        $loginRedirect .= '?redirectUri=' . urlencode($redirectUri);
      }

      // Add call
      $this->callOrRedirect($loginRedirect);
    }

    // Do nothing e.g., to let the parent application handle the redirection
    // if (! $authHandler->hasValidToken()) { do something }
  }

  /**
   * Logout of the SaaS application and remove the token from the storage provider
   *
   * @return void
   */
  public function logout($redirect = true)
  {
    // If there is no local token, then nothing to do
    if ($this->tokenString) {
      // Check that a logout endpoint has been provided
      if (isset($this->options['logoutEndpoint']) && ($logoutEndpoint = $this->options['logoutEndpoint'])) {
        // Revoke token in SaaS application via API call
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $logoutEndpoint);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
          'Content-Type: application/json',
          'Authorization: Bearer ' . $this->tokenString,
        ]);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_exec($curl);
        curl_close($curl);
      }

      // And remove the token from local storage
      $this->storage->removeToken();
      $this->tokenString = $this->tokenObject = null;
    }

    // Redirect to login page
    if ($redirect) {
      $this->loginRedirect();
    }
  }

  /**
   * Returns true/false respectively for a valid/invalid token
   *
   * @return bool
   */
  public function hasValidToken()
  {
    return !!$this->tokenObject;
  }

  /**
   * Returns the token object
   * NOTE: this is NOT the token string, but the decoded token provided
   * by the validator class
   *
   * @return mixed
   */
  public function token()
  {
    return $this->tokenObject;
  }
}
