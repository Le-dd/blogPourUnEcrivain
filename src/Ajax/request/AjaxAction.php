<?php

namespace App\Ajax\request;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Renderer\RendererInterface;
use App\Model\CategoryTable;
use GuzzleHttp\Psr7\Response;


class AjaxAction {

/**
 * @var CategoryTable
 */
  private $categoryTable;

/**
 * @var RendererInterface
 */
  private $renderer;

  public function __construct(RendererInterface $renderer,CategoryTable $categoryTable )
  {
    $this->renderer = $renderer;
    $this->categoryTable = $categoryTable;
  }
  public function __invoke(ServerRequestInterface $request)
  {
    $result = $this->categoryTable->findAllAjax();

    $result = json_encode($result);

    return new Response(200, [], $result );

  }

}
