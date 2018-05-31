<?php
namespace Framework;
use\GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use \DI\ContainerBuilder;
use Framework\Middleware\RoutePrefixeMiddleware;
use Framework\Router;

class App implements RequestHandlerInterface {
  /**
   * list of modules
   * @var array
   */
  private $modules = [];

  /**
   * @var array
   */
  private $definition;

  /**
   * @var ContainerInterface
   */
  private $container;

  /**
   * @var string[]
   */
  private $middlewares;

  /**
   * @var int
   */
  private $index = 0;

public function __construct(string $definition){

  $this->definition = $definition;

}

/**
 * Rajoute un module a l'application
 * @param  string $module
 * @return self
 */
public function addModule(string $module):self
{
  $this->modules[] = $module;
  return $this;
}

/**
 * Rajoute un middleware a l'application
 * @param  string $middleware
 * @return self
 */
public function pipe(string $routePrefix, ?string $middleware = null): self
{
  if ($middleware === null){
    $this->middlewares[] = $routePrefix;
  }else{
  $this->middlewares[] = new RoutePrefixeMiddleware($this->getContainer(), $routePrefix,  $middleware);
  }
  return $this;

}

public function handle(ServerRequestInterface $request): ResponseInterface
{
  $middleware = $this->getMiddleware();
  if(is_null($middleware)){
    throw new \Exception('Aucun middleware n\'a intercepté cette requête');
  }
  elseif (is_callable($middleware)) {
      return call_user_func_array($middleware, [$request,[$this,'handle']]);
        }
  elseif ($middleware instanceof MiddlewareInterface) {
      return $middleware->process($request, $this);
  }


}

public function run(ServerRequestInterface $request):ResponseInterface{

    foreach($this->modules as $module){
      $this->getContainer()->get($module);
    }
    return $this->handle($request);


  }

  public function getContainer(): ContainerInterface
  {

    if ($this->container === null) {

      $builder = new ContainerBuilder();
      $builder->addDefinitions($this->definition);
      foreach ($this->modules as $module){
        if ($module::DEFINITIONS){
          $builder->addDefinitions($module::DEFINITIONS);
        }
      }
      $this->container = $builder->build();
    }
    return $this->container;
  }

  private function getMiddleware()
  {
    if(array_key_exists($this->index, $this->middlewares))
    {
      if (is_string($this->middlewares[$this->index])){
        $middleware = $this->container->get($this->middlewares[$this->index]);
      }else{
        $middleware = $this->middlewares[$this->index];
      }
      $this->index++;

      return $middleware;
    }
    return null;

  }

  /**
   * @return array
   */
  public function getModules():array
  {
    return $this->modules;
  }



}
