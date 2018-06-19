<?php
namespace App\Ajax;


use Framework\Module;
use Psr\Container\ContainerInterface;
use Framework\Router;
use App\Ajax\request\AjaxAction;

use Framework\Renderer\RendererInterface;


class AjaxModule extends Module {


  const DEFINITIONS = __DIR__.'/config.php';

  public function __construct(RendererInterface $renderer,ContainerInterface $container)
  {

    $AjaxPrefix = $container->get('auth.ajax');
    $renderer->addPath('ajax',__DIR__.'/views');

    $router = $container->get(Router::class);
    $router->get("$AjaxPrefix/locationAll", AjaxAction::class,"Ajax.locationAll");



  }


}
