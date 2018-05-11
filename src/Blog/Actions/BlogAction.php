<?php
namespace App\Blog\Actions;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;

class BlogAction{

  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * @var Router
   */
  private $router;
  public function __construct(RendererInterface $renderer, \PDO $pdo, Router $router )
  {
    $this->renderer = $renderer;
    $this->pdo = $pdo;
    $this->router = $router;
  }
   public function __invoke(Request $request)
  {

    if($request->getAttribute('id')){
      return $this->show($request);

    }

    return $this->index();

  }

  public function index()
  {
    $posts = $this->pdo
      ->query('SELECT * FROM post ORDER BY date DESC LIMIT 10')
      ->fetchAll();
    return $this->renderer->render('@blog/index', compact('posts'));
  }
/**
 * Affiche un article
 * @param  Request $request
 * @return ResponseInterface|string
 */
  public function show(Request $request)
  {
    $slug = $request->getAttribute('slug');
    $query = $this->pdo
      ->prepare('SELECT * FROM post WHERE id = ?');
    $query->execute([$request->getAttribute('id')]);
    $post = $query->fetch();
    
    if ($post->slug !== $slug) {
      $redirectUri = $this->router->generateUri('blog.show',[
          'slug'=> $post->slug,
          'id' => $post->id
      ]);
      return (new Response())
        ->withStatus(301)
        ->withHeader('location',$redirectUri);

    }
    return $this->renderer->render('@blog/show', [
      'post' => $post
    ]);
  }
}
