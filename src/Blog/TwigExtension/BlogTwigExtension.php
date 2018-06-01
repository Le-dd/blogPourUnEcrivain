<?php
namespace App\Blog\TwigExtension;
use App\Blog\InterfaceBlog\BlogWidgetInterface;

/**
 * serie d'extention concernant l'administration'
 */
class BlogTwigExtension extends \Twig_Extension {

  /**
  * @var BlogWidgetInterface[]
  */
  private $widgets;

  public function __construct(array $widgets){

    $this->widgets = $widgets;
  }

/**
 * @return \Twig_SimpleFunction[]
 */
  public function getFunctions(): array
  {
    return[
    new \Twig_SimpleFunction('blog_menu',[$this,'renderMenu'], ['is_safe' => ['html']])
    ];
  }


  public function renderMenu(): string
  {

    return array_reduce($this->widgets, function (string $html, BlogWidgetInterface $widget){

      return $html . $widget->renderMenu();

    },'');

  }
}
