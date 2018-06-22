<?php
namespace App\Blog\Controller;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;


class LegalAction{

  /**
  * @var RendererInterface
  */
  private $renderer;


  use RouterAwareAction;

  public function __construct(RendererInterface $renderer, Router $router )
  {
    $this->renderer = $renderer;

  }
   public function __invoke(Request $request)
  {


    return $this->renderer->render('@blog/mentionsLegals/mentionLegal');

  }


}
