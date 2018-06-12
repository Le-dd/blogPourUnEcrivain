<?php
namespace App\Admin;

use Framework\Module;
use Framework\Router;
use Framework\Renderer\RendererInterface;
use Framework\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use App\Admin\TwigExtension\AdminTwigExtension;
use Framework\Renderer\TwigRenderer;
use App\Admin\Controller\{
  DashboardAction,
  PostCrudAction,
  CategoryCrudAction,
  AdminLoginAction
};



class AdminModule extends Module
{

  const DEFINITIONS = __DIR__.'/config.php';

  public function __construct(ContainerInterface $container,RendererInterface $renderer,Router $router,  AdminTwigExtension $adminTwigExtension,SessionInterface $session)
  {
    $renderer->addPath('admin', __DIR__.'/views');
    $prefix= $container->get('admin.prefix');

    $router->get("$prefix/loginAdmin", AdminLoginAction::class, 'admin.login');
    $router->post("$prefix/loginAdmin/editMail", AdminLoginAction::class,'admin.login.editMail');
    $router->get("$prefix/loginAdmin/validMail", AdminLoginAction::class, 'admin.login.validMail');
    $router->post("$prefix/loginAdmin/editPass", AdminLoginAction::class,'admin.login.editPass');
    $router->post("$prefix/loginAdmin/editLog", AdminLoginAction::class,'admin.login.editLog');


    if($session->get('auth.permit') === '777' ){
      $router->get($prefix, DashboardAction::class, 'admin');
      $router->crud("$prefix/posts", PostCrudAction::class, 'blog.admin');
      $router->crud("$prefix/categories", CategoryCrudAction::class, 'blog.category.admin');
      $router->crud("$prefix/comments", CommentsCrudAction::class, 'blog.com.admin');
    }
   if($renderer instanceof TwigRenderer){
      $renderer->getTwig()->addExtension($adminTwigExtension);
    }
  }

}
