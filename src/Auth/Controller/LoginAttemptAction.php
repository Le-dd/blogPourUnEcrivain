<?php

namespace App\Auth\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Renderer\RendererInterface;
use App\Auth\ModelAuth\DatabaseAuth;
use Framework\Router;
use Framework\Actions\RouterAwareAction;
use Framework\Session\FlashService;
use Framework\Response\RedirectResponse;
use Framework\Session\SessionInterface;

class LoginAttemptAction {


  /**
   * @var RendererInterface
   */
  private $renderer;
  /**
   * @var DatabaseAuth
   */
  private $auth;

  /**
   * @var SessionInterface
   */
  private $session;
  /**
   * @var Router
   */
  private $router;

  use RouterAwareAction;

  public function __construct(RendererInterface $renderer, DatabaseAuth $auth, Router $router, SessionInterface $session )
  {
    $this->renderer = $renderer;
    $this->auth = $auth;
    $this->session = $session;
    $this->router = $router;
  }
  public function __invoke(ServerRequestInterface $request)
  {
    $params = $request->getParsedBody();
    $user = $this->auth->login($params['login'],$params['password']);
    $permission = $this->session->get('auth.permit');

    if($user) {

      if($permission === '777') {
        $params = $this->transformParams((array)$user);
        $this->session->set('OldLast.auth',$params['last_auth']);
        $params['last_auth'] = date("Y-m-d");
        $this->auth->getTable()->update($params['id'], $params);

        $path = $this->session->get('auth.redirect') ?: '/admin';
        $this->session->delete('auth.redirect');
        return new RedirectResponse($path);
      }
      if($permission === '117') {
        $params = $this->transformParams((array)$user);
        $params['last_auth' ] = date("Y-m-d");
        $this->auth->getTable()->update($params['id'], $params);
        $path = $this->router->generateUri('admin.login');
        $this->session->delete('auth.redirect');
        return new RedirectResponse($path);
      }


      $path = $this->router->generateUri('auth.login');
      $this->session->delete('auth.redirect');
      return new RedirectResponse($path);

    }else{

      (new FlashService($this->session))->error('Identifiant ou mot de passe incorrect');
      return $this->redirect('auth.login');
    }

  }
  private function transformParams(array $params){
    $arrayParams=[];
    foreach ($params as $key => $value) {
      if($key === "permissionId"){
        $key = 'permission_id';
      }
      if($key === "createDate"){
        $key = 'create_date';
      }
      if($key === "lastAuth"){
        $key = 'last_auth';
      }
      $arrayParams[$key]= $value;
    }

    return $arrayParams;
  }

}
