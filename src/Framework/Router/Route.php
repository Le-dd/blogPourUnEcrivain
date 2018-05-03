<?php
namespace Framework\Router;
/**
 * Class Route
 * matched route
 */
class Route {

  /**
   * @var string
   */
  private $name;

  /**
   * @var callable
   */
  private $callback;

  /**
   * @var array
   */
  private $parameters;

  public function __construct(string $name, callable $callback, array $parameterss){
  $this->name = $name;
  $this->callback = $callback;
  $this->parameters = $parameters;
  }
  /**
   * @return string
   */
  public function getName():string {
    return $this->name;
  }
  /**
   * @return callable
   */
  public function getCallback():callable {
    return $this->callback;
  }
  /**
   * Retrieve URL parameters
   * @return string[]
   */
  public function getParams():array  {
    return $this->parameters;
  }

}
