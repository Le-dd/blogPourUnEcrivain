<?php
namespace App\Admin;

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

  public Function __construct(RendererInterface $renderer, array $widgets)
  {
    $this->renderer =  $renderer;
    $this->widgets = $widgets;
  }

  public Function __invoke()
  {
    $widgets = array_reduce($this->widgets, function (string $html, AdminWidgetInterface $widget){

      return $html . $widget->render();

    },'');
    return $this->renderer->render('@admin/dashboard', compact('widgets'));
  }


}
