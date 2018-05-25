<?php
namespace App\Blog;
use App\Admin\AdminWidgetInterface ;
use Framework\Renderer\RendererInterface;
use App\Blog\Table\PostTable;

class BlogWidget implements AdminWidgetInterface {

  /**
   * @var RendererInterface
   */
  private $renderer;

  /**
   * @var PostTable
   */
  private $postTable;

  public Function __construct(RendererInterface $renderer, PostTable $postTable)
  {
    $this->renderer = $renderer;
    $this->postTable = $postTable;
  }

  public function render(): string{

    $count = $this->postTable->count();
    return $this->renderer->render('@blog/admin/widget', compact('count'));

  }

  public function renderMenu(): string
  {
      return $this->renderer->render('@blog/admin/menu');
  }

}
