<?php
namespace Framework\Renderer;

interface RendererInterface
{

  /**
   * permet de rajouter un chemin pour changer les vues
   * @param string $namespace
   * @param null/string $path
   */

  public function addPath(string $namespace, ?string $path= null);

/**
 * permet de rendre une vue
 * le chemin peut être précisé avec des namespace rajoutés via addPath()
 * $this->render('@blog/view');
 * $this->render('view');
 * @param  string $view
 * @param  array  $params
 * @return string
 */
  public function render(string $view,array $params = []):string;

/**
 * Permet de rjouter des variables globales à toutes les vues
 * @param string $kay   [description]
 * @param [type] $value [description]
 */
  public function addGlobal(string $key, $value);

}
