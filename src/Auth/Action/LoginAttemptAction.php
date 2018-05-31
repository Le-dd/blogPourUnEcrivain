<?php

namespace App\Auth\Action;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Renderer\RendererInterface;
use App\auth\DatabaseAuth;
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

  public function __construct(RendererInterface $renderer, DatabaseAuth $auth, Router $router, SessionInterface $session)
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
    if($user) {

      $path = $this->session->get('auth.redirect') ?: $this->router->generateUri('admin');
      $this->session->delete('auth.redirect');
      return new RedirectResponse($path);

    }else{

      (new FlashService($this->session))->error('Identifiant ou mot de passe incorrect');
      return $this->redirect('auth.login');
    }

  }

}
