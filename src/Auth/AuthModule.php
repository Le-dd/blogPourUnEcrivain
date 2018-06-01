<?php
namespace App\auth;


use Framework\Module;
use Psr\Container\ContainerInterface;
use Framework\Router;
use App\Auth\Controller\{
  LoginAction,
  LoginAttemptAction,
  LogoutAction};
use Framework\Renderer\RendererInterface;


class AuthModule extends Module {


  const DEFINITIONS = __DIR__.'/config.php';

  public function __construct(RendererInterface $renderer,ContainerInterface $container)
  {
    $renderer->addPath('auth', __DIR__.'/views');
    $authPrefix = $container->get('auth.login');
    $logoutPrefix= $container->get('auth.logout');
    $router = $container->get(Router::class);
    $router->get($authPrefix, LoginAction::class, 'auth.login');
    $router->post($authPrefix, LoginAttemptAction::class);
    $router->post($logoutPrefix, LogoutAction::class, 'auth.logout');
  }


}
