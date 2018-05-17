<?php
namespace Framework;

use Framework\Router\Route;
use Framework\Router\MiddleWareApp;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;
/**
 * Class Router
 * Register and matched route
 */
class Router{
  /**
   * @var fastRouteRouter
   */
  private $router;

  public function __construct(){
    $this->router = new FastRouteRouter();
  }
/**
 * @param  string   $path
 * @param  string|callable $callable
 * @param  string   $name
 */
public function get(string $path,$callable, ?string $name = null)
{
  $this->router->addRoute(new ZendRoute($path, new MiddlewareApp($callable), ['GET'], $name));
}

/**
 * @param  string   $path
 * @param  string|callable $callable
 * @param  string   $name
 */
public function post(string $path,$callable, ?string $name = null)
{
  $this->router->addRoute(new ZendRoute($path, new MiddlewareApp($callable), ['POST'], $name));
}

/**
 * @param  serverRequestInterface $request
 * @return Route|null
 */
public function match(serverRequestInterface $request): ?Route
{
  $result = $this->router->match($request);
  if ($result->isSuccess()){
    return new Route(
      $result->getMatchedRouteName(),
      $result->getMatchedRoute()->getMiddleware()->getCallable(),
      $result->getMatchedParams()
      );
  }
  return null;

}

public function generateUri(string $name, array $params =[], array $queryParams = []): ?string
 {
  $uri = $this->router->generateUri($name, $params);
  if(!empty($queryParams)){
    return $uri . '?' . http_build_query($queryParams);
  }
  return $uri;
}
}
