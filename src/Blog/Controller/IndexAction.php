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
use App\Model\PostTable;

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

  /**
   * @var PostTable
   */
  private $postTable;

  use RouterAwareAction;

  public function __construct(RendererInterface $renderer, Router $router, FirstPageTable $firstPageTable,ImageTable $imageTable, PostTable $postTable  )
  {
    $this->renderer = $renderer;
    $this->firstPageTable = $firstPageTable;
    $this->imageTable = $imageTable;
    $this->postTable = $postTable;
  }
   public function __invoke(Request $request)
  {

    $params = $request->getQueryParams();
    $item = $this->firstPageTable->find("1");
    $last = $this->postTable->findLast();
    $last = $last[0];
    $image1 =$this->imageTable->find($item->imgFond);
    $image2 = $this->imageTable->find($item->imgProfil);
    return $this->renderer->render('@blog/index', compact('item','image1','image2','last'));

  }


}
