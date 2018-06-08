<?php

namespace Framework\Auth;

use Framework\Auth;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Session\SessionInterface;


class LoggedinMiddleware implements MiddlewareInterface {

  public $auth;
  public $session;


  public function __construct (Auth $auth,SessionInterface $session ) {

      $this->auth = $auth;
      $this->session = $session;
  }

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {

    $user = $this->auth->getUser();

    if(is_null($user)){

      throw new ForbiddenException();

    }
    return $handler->handle($request->withAttribute('user',$user));

  }


}
