<?php
namespace Framework\Twig;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Framework\Router;

class ImageExtension extends \Twig_Extension {

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
      new \Twig_SimpleFunction('imageType',[$this,'image'], ['is_safe' =>['html']])
      ];
    }


    public function image(string $url,string $type):string
    {
      $extention = substr($url,-4);
      $url= str_replace($extention,'',$url);
          return "/images/{$url}_{$type}.jpg";
      }



}
