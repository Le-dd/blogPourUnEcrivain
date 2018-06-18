<?php
namespace App\Blog\Controller;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use App\Model\FirstPageTable;
use App\Model\ImageTable;

class IndexAction{

  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var FirstPageTable
   */
  private $firstPageTable;

  /**
   * @var ImageTable
   */
  private $imageTable;

  use RouterAwareAction;

  public function __construct(RendererInterface $renderer, Router $router, FirstPageTable $firstPageTable,ImageTable $imageTable )
  {
    $this->renderer = $renderer;
    $this->firstPageTable = $firstPageTable;
    $this->imageTable = $imageTable;
  }
   public function __invoke(Request $request)
  {

    $params = $request->getQueryParams();
    $item = $this->firstPageTable->find("1");

    $image1 =$this->imageTable->find($item->imgFond);
    $image2 = $this->imageTable->find($item->imgProfil);
    return $this->renderer->render('@blog/index', compact('item','image1','image2'));

  }


}
