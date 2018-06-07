<?php
namespace App\Admin\TwigExtension;
use App\Admin\InterfaceAdmin\AdminWidgetInterface;
use Framework\Session\SessionInterface;

/**
 * serie d'extention concernant l'administration'
 */
class AdminTwigExtension extends \Twig_Extension {

  /**
  * @var AdminWidgetInterface[]
  */
  private $widgets;
  /**
  * @var SessionInterface
  */
  private $session;

  public function __construct(array $widgets,SessionInterface $session){

    $this->widgets = $widgets;
    $this->session = $session;
  }

/**
 * @return \Twig_SimpleFunction[]
 */
  public function getFunctions(): array
  {
    return[
    new \Twig_SimpleFunction('admin_menu',[$this,'renderMenu'], ['is_safe' => ['html']]),
    new \Twig_SimpleFunction('admin_menu_admin',[$this,'renderMenuAdmin'], ['is_safe' => ['html']])
    ];
  }


  public function renderMenu(): string
  {

    return array_reduce($this->widgets, function (string $html, AdminWidgetInterface $widget){

      return $html . $widget->renderMenu();

    },'');

  }

  public function renderMenuAdmin()
  {
    if($this->session->get('auth.permit') === '777'){

    return array_reduce($this->widgets, function (string $html, AdminWidgetInterface $widget){

      return $html . $widget->renderMenuAdmin();

    },'');
    }
  }


}
