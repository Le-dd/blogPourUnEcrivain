<?php
namespace App\auth;


use Framework\Module;
use Psr\Container\ContainerInterface;
use Framework\Router;
use App\Auth\Controller\{
  LoginAction,
  LoginAttemptAction,
  LogoutAction,
  LoginCrudAction,
  LoginCreateAction,
  LoginEditePassAction
};
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


    $router->get("$authPrefix/blog/new", LoginCreateAction::class, "auth.crud.create");
    $router->post("$authPrefix/blog/new", LoginCreateAction::class);
    $router->get("$authPrefix/valide/valideCreate", LoginCreateAction::class, "auth.crud.validcre");

    $router->get("$authPrefix/blog/edit", LoginEditePassAction::class, "auth.crud.editepass");
    $router->post("$authPrefix/blog/edit", LoginEditePassAction::class);
    $router->get("$authPrefix/valide/validEpass", LoginEditePassAction::class, "auth.crud.validpass");
    $router->post("$authPrefix/valide/validEpass", LoginEditePassAction::class);



  }


}
