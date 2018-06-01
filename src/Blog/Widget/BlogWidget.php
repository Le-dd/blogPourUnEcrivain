<?php
namespace App\Blog\Widget;
use App\Blog\InterfaceBlog\BlogWidgetInterface;
use Framework\Renderer\RendererInterface;
use App\Model\PostTable;

class BlogWidget implements BlogWidgetInterface {

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
    return $this->renderer->render('@blog/widget/widget', compact('count'));

  }

  public function renderMenu(): string
  {
      return $this->renderer->render('@blog/widget/menu');
  }

}
