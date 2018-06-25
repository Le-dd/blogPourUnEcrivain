<?php
namespace App\Admin\Widget;
use App\Admin\InterfaceAdmin\AdminWidgetInterface;
use Framework\Renderer\RendererInterface;
use Framework\Session\SessionInterface;
use App\Model\UserTable;

class AdminWidget implements AdminWidgetInterface {

  /**
   * @var RendererInterface
   */
  private $renderer;


  /**
   * @var UserTable
   */
  private $userTable;

  /**
   * @var SessionInterface
   */
  private $session;

  public function __construct(RendererInterface $renderer, UserTable $userTable,SessionInterface $session)
  {
    $this->renderer = $renderer;
    $this->userTable = $userTable;
    $this->session = $session;
  }

  public function render(): string{

    $params['date']= $this->session->get('OldLast.auth');
    $count = $this->userTable->findAllNewUser($params);
    return $this->renderer->render('@admin/widget/widget', compact('count'));

  }

  public function renderWidgetUser(): string{

    $count = $this->userTable->count();
    return $this->renderer->render('@admin/widget/widgetUser', compact('count'));

  }

  public function renderWidgetULC(): string{
    $params['date']= $this->session->get('OldLast.auth');
    $count = $this->userTable->findAllLastCon($params)-1;
    return $this->renderer->render('@admin/widget/widgetUserLastConnect', compact('count'));

  }

  public function renderMenu(): string
  {
      return $this->renderer->render('@admin/widget/menu');
  }

  public function renderMenuAdmin(): string
  {
      return $this->renderer->render('@admin/widget/menuAdmin');
  }

}
