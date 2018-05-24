<?php
namespace App\Blog\Actions;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use App\Blog\Table\PostTable;
use App\Blog\Table\CategoryTable;

class IndexAction{

  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var PostTable
   */
  private $postTable;


  /**
   * @var CategoryTable
   */
  private $categoryTable;

  use RouterAwareAction;

  public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable, CategoryTable $categoryTable )
  {
    $this->renderer = $renderer;
    $this->postTable = $postTable;
    $this->categoryTable = $categoryTable;
  }
   public function __invoke(Request $request)
  {

    $params = $request->getQueryParams();
    $posts = $this->postTable->findPaginatedPublic(6, $params['p'] ?? 1);
    return $this->renderer->render('@blog/index', compact('posts'));

  }


}
