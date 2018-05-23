<?php
namespace App\Blog;

use Framework\Router;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use App\Blog\Actions\BlogAction;
use App\Blog\Actions\PostCrudAction;
use App\Blog\Actions\CategoryCrudAction;
use Psr\Container\ContainerInterface;

class BlogModule extends Module {

  const DEFINITIONS = __DIR__.'/config.php';



  public function __construct(ContainerInterface $container )
  {
    $blogPrefix = $container->get('blog.prefix');
    $container->get(RendererInterface::class)->addPath('blog',__DIR__.'/views');
    $router =  $container->get(Router::class);
    $router->get($blogPrefix, BlogAction::class, 'blog.index');
    $router->get(
      "$blogPrefix/{slug:[a-z\-0-9]+}-{id:[0-9]+}",
     BlogAction::class, 'blog.show');

    if($container->has('admin.prefix')){
        $prefix= $container->get('admin.prefix');
        $router->crud("$prefix/posts", PostCrudAction::class, 'blog.admin');
        $router->crud("$prefix/categories", CategoryCrudAction::class, 'blog.category.admin');

    };


  }

}
