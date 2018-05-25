<?php
namespace Framework;

use Framework\Router\Route;
use Framework\Middleware\CallableMiddleware;
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
  $this->router->addRoute(new ZendRoute($path, new CallableMiddleware($callable), ['GET'], $name));
}

/**
 * @param  string   $path
 * @param  string|callable $callable
 * @param  string   $name
 */
public function post(string $path,$callable, ?string $name = null)
{
  $this->router->addRoute(new ZendRoute($path, new CallableMiddleware($callable), ['POST'], $name));
}

/**
 * @param  string   $path
 * @param  string|callable $callable
 * @param  string   $name
 */
public function delete(string $path,$callable, ?string $name = null)
{
  $this->router->addRoute(new ZendRoute($path, new CallableMiddleware($callable), ['DELETE'], $name));
}

/**
 * Génère les route du CRUD
 * @param  string $prefixPath
 * @param  $callable
 * @param  string $prefixName
  */
public function crud(string $prefixPath,$callable, string $prefixName )
{
  $this->get("$prefixPath", $callable, "$prefixName.index");
  $this->get("$prefixPath/new", $callable, "$prefixName.create");
  $this->post("$prefixPath/new", $callable );
  $this->get("$prefixPath/{id:\d+}", $callable, "$prefixName.edit");
  $this->post("$prefixPath/{id:\d+}", $callable);
  $this->delete("$prefixPath/{id:\d+}", $callable, "$prefixName.delete");
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
