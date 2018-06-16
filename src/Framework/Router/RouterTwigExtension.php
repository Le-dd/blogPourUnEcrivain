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
      new \Twig_SimpleFunction('path', [$this,'pathFor']),
      new \Twig_SimpleFunction('is_subpath', [$this,'isSubPath']),
      new \Twig_SimpleFunction('is_firstpath', [$this,'isFirstPath']),
      new \Twig_SimpleFunction('host_serveur', [$this,'hostServeur'])
    ];
  }

  public function pathFor(string $path, array $params = []): string
  {

      return $this->router->generateUri($path,$params);
  }
  public function hostServeur(): string
  {

      return "http://".$_SERVER['HTTP_HOST'];
  }


  public function isSubPath(string $path): bool
  {
      $uri = $_SERVER['REQUEST_URI'] ?? '/';
      $expectedUri = $this->router->generateUri($path);
      return strpos($uri, $expectedUri) !== false;
  }

  public function isFirstPath(string $path): bool
  {
      $uri = $_SERVER['REQUEST_URI'] ?? '/';
      $expectedUri = $this->router->generateUri($path);
      if($uri === $expectedUri){
        $result = true;
      }else{
        $result = False;
      }
      return $result ;
  }
}
