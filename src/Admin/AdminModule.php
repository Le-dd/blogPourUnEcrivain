<?php
namespace App\Admin;

use Framework\Module;
use Framework\Router;
use Framework\Renderer\RendererInterface;
use App\Admin\DashboardAction;
use App\Admin\AdminTwigExtension;
use Framework\Renderer\TwigRenderer;

class AdminModule extends Module
{

  const DEFINITIONS = __DIR__.'/config.php';

  public function __construct(RendererInterface $renderer,Router $router, string $prefix, AdminTwigExtension $adminTwigExtension)
  {
    $renderer->addPath('admin', __DIR__.'/views');
    $router->get($prefix, DashboardAction::class, 'admin');
    if($renderer instanceof TwigRenderer){
      $renderer->getTwig()->addExtension($adminTwigExtension);
    }
  }

}
