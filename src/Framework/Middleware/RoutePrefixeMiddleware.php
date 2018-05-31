<?php

namespace Framework\Middleware;

use Framework\Auth;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class RoutePrefixeMiddleware implements MiddlewareInterface {

  /**
   * @var ContainerInterface
   */
  public $container;

  /**
   * @var string
   */
  public $prefix;

  /**
   * @var string
   */
  public $middleware;

  public function __construct (ContainerInterface $container, string $prefix, string $middleware) {

      $this->container = $container;
      $this->prefix = $prefix;
      $this->middleware = $middleware;
  }

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    $path=$request->getUri()->getPath();
    if (strpos($path, $this->prefix) === 0){
      return $this->container->get($this->middleware)->process($request,$handler);
    }
    return $handler->handle($request);

  }


}
