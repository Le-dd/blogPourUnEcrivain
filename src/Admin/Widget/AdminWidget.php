<?php
namespace App\Admin\Widget;
use App\Admin\InterfaceAdmin\AdminWidgetInterface;
use Framework\Renderer\RendererInterface;
use App\Model\PostTable;

class AdminWidget implements AdminWidgetInterface {

  /**
   * @var RendererInterface
   */
  private $renderer;

  /**
   * @var PostTable
   */
  private $postTable;

  public function __construct(RendererInterface $renderer, PostTable $postTable)
  {
    $this->renderer = $renderer;
    $this->postTable = $postTable;
  }

  public function render(): string{

    $count = $this->postTable->count();
    return $this->renderer->render('@admin/widget/widget', compact('count'));

  }

  public function renderMenu(): string
  {
      return $this->renderer->render('@admin/widget/menu');
  }

}
