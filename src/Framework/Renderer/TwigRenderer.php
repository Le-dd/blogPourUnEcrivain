<?php
namespace Framework\Renderer;


class TwigRenderer implements RendererInterface{

  const DEFAULT_NAMESPACE='__MAIN';

  private $twig;

  private $loader;



  public function __construct(string $path)
   {

      $this->loader = new \Twig_Loader_Filesystem($path);
      $this->twig = new \Twig_Environment($this->loader,[]);

  }
  /**
   * permet de rajouter un chemin pour changer les vues
   * @param string $namespace
   * @param null/string $path
   */

  public function addPath(string $namespace, ?string $path= null)
  {
    return $this->loader->addPath($path,$namespace);
  }

/**
 * permet de rendre une vue
 * le chemin peut être précisé avec des namespace rajoutés via addPath()
 * $this->render('@blog/view');
 * $this->render('view');
 * @param  string $view
 * @param  array  $params
 * @return string
 */
  public function render(string $view,array $params = []):string
  {
      return $this->twig->render($view.'.twig', $params);
  }

/**
 * Permet de rjouter des variables globales à toutes les vues
 * @param string $kay   [description]
 * @param [type] $value [description]
 */
  public function addGlobal(string $key, $value)
  {
      return $this->twig->addGlobal($key, $value);
  }



}
