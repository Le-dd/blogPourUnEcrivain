<?php

namespace Framework\Auth;

use Framework\Auth;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Response\RedirectResponse;
use Framework\Session\SessionInterface;
use Framework\Session\FlashService;

class ForbiddenMiddleware implements MiddlewareInterface {

  public $LoginPath;

  public $session;

  public function __construct (string $LoginPath, SessionInterface $session) {

      $this->LoginPath = $LoginPath;
      $this->session = $session;
  }

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {

    try{
      return $handler->handle($request);
    }catch(ForbiddenException $exception){

      $this->session->set('auth.redirect',$request->getUri()->getPath());
      (new FlashService($this->session))->error('Vous devez posséder un compte pour accéder à cette page');
      return new RedirectResponse($this->LoginPath);
    }


  }


}
