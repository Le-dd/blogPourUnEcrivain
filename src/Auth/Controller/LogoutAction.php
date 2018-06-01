<?php

namespace App\Auth\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Renderer\RendererInterface;
use Framework\Response\RedirectResponse;
use App\Auth\ModelAuth\DatabaseAuth;
use Framework\Session\FlashService;



class LogoutAction {


  /**
   * @var RendererInterface
   */
  private $renderer;

  /**
   * @var DatabaseAuth
   */
  private $auth;


  /**
   * @var FlashService
   */
  private $flashService;



  public function __construct(RendererInterface $renderer, DatabaseAuth $auth,  FlashService $flashService)
  {
    $this->renderer = $renderer;
    $this->auth = $auth;
    $this->flashService = $flashService;

  }
  public function __invoke(ServerRequestInterface $request)
  {
    $this->auth->logout();
    $this->flashService->success('Vous êtes maintenant déconnecté');
    return new RedirectResponse('/');

  }

}
