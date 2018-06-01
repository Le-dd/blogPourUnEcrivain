<?php
namespace App\Blog\InterfaceBlog;


interface BlogWidgetInterface {

  public function render(): string;

  public function renderMenu(): string;


}
