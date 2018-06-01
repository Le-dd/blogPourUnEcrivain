<?php
namespace App\Admin\InterfaceAdmin;


interface AdminWidgetInterface {

  public function render(): string;

  public function renderMenu(): string;


}
