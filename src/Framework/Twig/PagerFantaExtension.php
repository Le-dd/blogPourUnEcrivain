<?php
namespace Framework\Twig;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Framework\Router;

class PagerFantaExtension extends \Twig_Extension {

  /**
   * @var Router
   */
    private $router;

    public function __construct(Router $router)
    {

      $this->router = $router;
    }

    public function getFunctions()
    {
      return[
      new \Twig_SimpleFunction('paginate',[$this,'paginate'], ['is_safe' =>['html']])
      ];
    }


    public function paginate(Pagerfanta $paginatedResults, string $route, array $routerParams = [], array $queryArgs = []):string
    {
      $view = new TwitterBootstrap4View();
      return $view->render($paginatedResults,function(int $page) use ($route,$routerParams ,$queryArgs){
        if ($page > 1){
        $queryArgs['p'] = $page;
        }
        return $this->router->generateUri($route, $routerParams, $queryArgs);

      });

    }

}
