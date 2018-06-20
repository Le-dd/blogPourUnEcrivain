<?php

namespace App\Ajax\request;

use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\RendererInterface;
use App\Model\CategoryTable;
use App\Model\PostTable;
use GuzzleHttp\Psr7\Response;


class AjaxAction {

/**
 * @var PostTable
 */
  private $categoryTable;

/**
 * @var CategoryTable
 */
  private $postTable;

/**
 * @var RendererInterface
 */
  private $renderer;

  public function __construct(RendererInterface $renderer,CategoryTable $categoryTable,PostTable $postTable )
  {
    $this->renderer = $renderer;
    $this->categoryTable = $categoryTable;
    $this->postTable = $postTable;
  }



  public function __invoke(Request $request)
 {

   $idLoc = $request->getAttribute('idLoc');


   if($idLoc){
     return $this->subMarker($request);
   }



   return $this->index($request);

 }
  public function index(Request $request)
  {
    $result = $this->categoryTable->findAllAjax();

    $result = json_encode($result);

    return new Response(200, [], $result );

  }

  public function subMarker(Request $request)
  {
    $params['locationId'] =$request->getAttribute('idLoc');
    $result = $this->postTable->findAllByAjax($params);

    $result = json_encode($result);

    return new Response(200, [], $result );

  }

}
