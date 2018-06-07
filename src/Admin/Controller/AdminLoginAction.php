<?php
namespace App\Admin\Controller;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use App\Model\PostTable;
use App\Model\CategoryTable;

class AdminLoginAction{

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



    return $this->renderer->render('@admin/login/login');

  }


}
