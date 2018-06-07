<?php

namespace Framework\Auth;

use Framework\Auth;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Session\SessionInterface;
use App\Model\PermissionTable;

class LoggedinMiddleware implements MiddlewareInterface {

  public $auth;
  public $session;
  public $permissionTable;

  public function __construct (Auth $auth,SessionInterface $session,PermissionTable $permissionTable ) {

      $this->auth = $auth;
      $this->session = $session;
      $this->permissionTable = $permissionTable;
  }

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {

    $user = $this->auth->getUser();
    if($user) {
    $permission = $this->permissionTable->findBY('id',$user->getRoles());
    $this->session->set('auth.permit', $permission->getPermit());
    }
    if(is_null($user)){

      throw new ForbiddenException();

    }
    return $handler->handle($request->withAttribute('user',$user));

  }


}
