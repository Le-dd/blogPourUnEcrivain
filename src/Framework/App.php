<?php
namespace Framework;
use\GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use \DI\ContainerBuilder;

use Framework\Router;

class App {
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
public function pipe(string $middleware): self
{

  $this->middlewares[] = $middleware;
  return $this;

}

public function process(ServerRequestInterface $request): ResponseInterface
{
  $middleware = $this->getMiddleware();
  if(is_null($middleware)){
    throw new \Exception('Aucun middleware n\'a intercepté cette requête');
  }
    return call_user_func_array($middleware, [$request,[$this,'process']]);


  

}

public function run(ServerRequestInterface $request):ResponseInterface{

    foreach($this->modules as $module){
      $this->getContainer()->get($module);
    }
    return $this->process($request);


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
      $middleware =  $this->container->get($this->middlewares[$this->index]);
      $this->index++;

      return $middleware;
    }
    return null;

  }


}
