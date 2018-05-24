<?php
namespace App\Blog;

use Framework\Router;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use App\Blog\Actions\IndexAction;
use App\Blog\Actions\CategoryAction;
use App\Blog\Actions\PostIndexAction;
use App\Blog\Actions\PostShowAction;
use App\Blog\Actions\PostCrudAction;
use App\Blog\Actions\CategoryCrudAction;
use Psr\Container\ContainerInterface;

class BlogModule extends Module {

  const DEFINITIONS = __DIR__.'/config.php';



  public function __construct(ContainerInterface $container )
  {
    $indexPrefix = $container->get('index.prefix');
    $categoryPrefix = $container->get('category.prefix');
    $postPrefix = $container->get('posts.prefix');

    $container->get(RendererInterface::class)->addPath('blog',__DIR__.'/views');
    $router =  $container->get(Router::class);

    $router->get($indexPrefix, IndexAction::class, 'blog.index');
    $router->get($categoryPrefix, CategoryAction::class, 'blog.category');
    $router->get($postPrefix, PostIndexAction::class, 'blog.posts.index');
    $router->get(
      "$postPrefix/{slug:[a-z\-0-9]+}-{id:[0-9]+}",
     PostShowAction::class, 'blog.posts.show');

    if($container->has('admin.prefix')){
        $prefix= $container->get('admin.prefix');
        $router->crud("$prefix/posts", PostCrudAction::class, 'blog.admin');
        $router->crud("$prefix/categories", CategoryCrudAction::class, 'blog.category.admin');

    };


  }

}
