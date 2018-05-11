<?php
namespace App\Blog\Actions;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;

class BlogAction{

  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var \PDO
   */
  private $pdo;

  public function __construct(RendererInterface $renderer, \PDO $pdo )
  {
    $this->renderer = $renderer;
    $this->pdo = $pdo;
  }
   public function __invoke(Request $request)
  {
    $slug = $request->getAttribute('slug');
    if($slug){
      return $this->show($slug);

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

  public function show(string $slug): string
  {
    return $this->renderer->render('@blog/show', [
      'slug' => $slug
    ]);
  }
}
