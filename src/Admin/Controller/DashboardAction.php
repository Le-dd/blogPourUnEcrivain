<?php
namespace App\Admin\Controller;
use App\Admin\InterfaceAdmin\AdminWidgetInterface;

use \Framework\Renderer\RendererInterface;

class DashboardAction {
/**
 * @var RendererInterface
 */
  Private $renderer;
  /**
   * @var AdminWidgetInterface[]
   */
  Private $widgets;

  public function __construct(RendererInterface $renderer, array $widgets)
  {
    $this->renderer =  $renderer;
    $this->widgets = $widgets;
  }

  public function __invoke()
  {
    $widgets = array_reduce($this->widgets, function (string $html, AdminWidgetInterface $widget){

      return $html . $widget->render();

    },'');
    return $this->renderer->render('@admin/dashboard', compact('widgets'));
  }


}
