<?php
namespace Framework\Router;

use Framework\Router;

class RouterTwigExtension extends \twig_Extension{

/**
 * @var Router
 */
  private $router;

  public function __construct(Router $router)
  {
    $this->router = $router;
  }

  public function getfunctions()
  {
    return[
      new \Twig_SimpleFunction('path', [$this,'pathFor'])
    ];
  }

  public function pathFor(string $path, array $params = []): string
  {
      return $this->router->generateUri($path,$params);
  }
}
