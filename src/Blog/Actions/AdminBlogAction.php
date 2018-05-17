<?php
namespace App\Blog\Actions;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use App\Blog\Table\PostTable;


class AdminBlogAction{
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

    if($request->getAttribute('id')){
      return $this->edit($request);

    }

    return $this->index($request);

  }

  public function index(Request $request)
  {
    $params = $request->getQueryParams();
    $items = $this->postTable->findPaginated(6, $params['p'] ?? 1);
    return $this->renderer->render('@blog/admin/index', compact('items'));
  }

  public function edit(Request $request)
  {
    $item = $this->postTable->find($request->getAttribute('id'));
    return $this->renderer->render('@blog/admin/edit', compact('item'));
  }
  }
