<?php
namespace App\Blog\Controller;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use App\Model\PostTable;
use App\Model\CategoryTable;

class PostShowAction{

  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var PostTable
   */
  private $postTable;

  /**
   * @var Router
   */
  private $router;



  use RouterAwareAction;

  public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable )
  {
    $this->renderer = $renderer;
    $this->postTable = $postTable;
    $this->router = $router;

  }
   public function __invoke(Request $request)
  {

    $slug = $request->getAttribute('slug');
    $post = $this->postTable->find($request->getAttribute('id'));

  if ($post->slug !== $slug) {
    return $this->redirect('blog.posts.show',[
        'slug'=> $post->slug,
        'id' => $post->id
    ]);

  }
  return $this->renderer->render('@blog/posts/show', [
    'post' => $post
  ]);
  }



}
