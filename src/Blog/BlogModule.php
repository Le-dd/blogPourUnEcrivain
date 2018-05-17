<?php
namespace App\Blog;

use Framework\Router;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use App\Blog\Actions\BlogAction;
use App\Blog\Actions\AdminBlogAction;
use Psr\Container\ContainerInterface;

class BlogModule extends Module {

  const DEFINITIONS = __DIR__.'/config.php';



  public function __construct(ContainerInterface $container )
  {
    $container->get(RendererInterface::class)->addPath('blog',__DIR__.'/views');
    $router =  $container->get(Router::class);
    $router->get($container->get('blog.prefix'), BlogAction::class, 'blog.index');
    $router->get($container->get('blog.prefix') . '/{slug:[a-z\-0-9]+}-{id:[0-9]+}', BlogAction::class, 'blog.show');

    if($container->has('admin.prefix')){
        $prefix= $container->get('admin.prefix');
        $router->get("$prefix/posts", AdminBlogAction::class, 'admin.blog.index');
        $router->get("$prefix/posts/{id:\d+}", AdminBlogAction::class, 'admin.blog.edit');
        $router->post("$prefix/posts/{id:\d+}", AdminBlogAction::class);
    };
  }

}
