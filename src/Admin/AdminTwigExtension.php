<?php
namespace App\Admin;

/**
 * serie d'extention concernant l'administration'
 */
class AdminTwigExtension extends \Twig_Extension {

  /**
  * @var AdminWidgetInterface[]
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
    new \Twig_SimpleFunction('admin_menu',[$this,'renderMenu'], ['is_safe' => ['html']])
    ];
  }


  public function renderMenu(): string
  {

    return array_reduce($this->widgets, function (string $html, AdminWidgetInterface $widget){

      return $html . $widget->renderMenu();

    },'');

  }
}
