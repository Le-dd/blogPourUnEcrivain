<?php
namespace Framework\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;
use Framework\Router\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

class DispatcherMiddleware {

  /**
   * @var ContainerInterface
   */
  private $container;
  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
  {

        $route = $request->getAttribute(Route::class);
        if(is_null($route)){
          return $next($request);
        }
        $callback = $route->getCallback();
        if (is_string($callback)){
          $callback = $this->container->get($callback);
        }
        $response = call_user_func_array($callback, [$request]);
        if (is_string($response)) {
          return new Response(200, [], $response);

        }elseif($response instanceof ResponseInterface){
          return $response;

        }else{
          throw new \Exception('the response is not a string or an instance of ResponseInterface ');
        }
    }

}
