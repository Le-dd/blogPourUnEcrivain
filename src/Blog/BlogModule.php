<?php
namespace App\Blog;

use Framework\Router;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use App\Blog\Controller\{
  IndexAction,
  CategoryAction,
  PostIndexAction,
  PostShowAction,
  CommentsAction
};
use Psr\Container\ContainerInterface;
use  App\Blog\TwigExtension\BlogTwigExtension;
use Framework\Renderer\TwigRenderer;


class BlogModule extends Module {

  const DEFINITIONS = __DIR__.'/config.php';



  public function __construct(ContainerInterface $container, BlogTwigExtension $blogTwigExtension, RendererInterface $renderer )
  {
    $indexPrefix = $container->get('index.prefix');
    $categoryPrefix = $container->get('category.prefix');
    $postPrefix = $container->get('posts.prefix');

    $renderer->addPath('blog',__DIR__.'/views');
    $router =  $container->get(Router::class);

    $router->get($indexPrefix, IndexAction::class, 'blog.index');
    $router->get($categoryPrefix, CategoryAction::class, 'blog.category');
    $router->get($postPrefix, PostIndexAction::class, 'blog.posts.index');
    $router->get(
      "$postPrefix/{slug:[a-z\-0-9]+}-{id:[0-9]+}",
     PostShowAction::class, 'blog.posts.show');
    $router->post("$postPrefix/comPost", CommentsAction::class, 'blog.com.comPost');

     if($renderer instanceof TwigRenderer){
       $renderer->getTwig()->addExtension($blogTwigExtension);
     }




  }

}
