<?php
namespace App\Blog\TwigExtension;
use App\Blog\InterfaceBlog\BlogWidgetInterface;
use App\Model\CommentaryTable;
use Framework\Renderer\RendererInterface;
use Framework\Session\SessionInterface;

/**
 * serie d'extention concernant le blog'
 */
class BlogTwigExtension extends \Twig_Extension {

  /**
  * @var BlogWidgetInterface[]
  */
  private $widgets;

  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
  * @var CommentaryTable
  */
  private $commentaryTable;

  /**
  * @var SessionInterface
  */
  private $session;

  public function __construct(array $widgets,RendererInterface $renderer,CommentaryTable $commentaryTable, SessionInterface $session){

    $this->widgets = $widgets;
    $this->renderer = $renderer;
    $this->commentaryTable = $commentaryTable;
    $this->session = $session;
  }

/**
 * @return \Twig_SimpleFunction[]
 */
  public function getFunctions(): array
  {
    return[
    new \Twig_SimpleFunction('blog_menu',[$this,'renderMenu'], ['is_safe' => ['html']]),
    new \Twig_SimpleFunction('create_com',[$this,'createCom'], ['is_safe' => ['html']]),
    new \Twig_SimpleFunction('affiche_com',[$this,'afficheCom'], ['is_safe' => ['html']])
    ];
  }


  public function renderMenu(): string
  {

    return array_reduce($this->widgets, function (string $html, BlogWidgetInterface $widget){

      return $html . $widget->renderMenu();

    },'');

  }
  public function afficheCom(string $idPost,string $slugPost,?string $idCom = null): string
  {
        $params['postId'] = $idPost;
        $idComSave = $idCom;

        if(is_null($idCom)){
          $comments = $this->commentaryTable->findAllNull($params)->count();
        }else {

          $params['commentId'] = $idCom;
          $comments = $this->commentaryTable->findAllBy($params)->count();
        }


        if($comments !== 0)
        {
          if(is_null($idCom)){
            $comments = $this->commentaryTable->findAllNull($params)->fetchAll();
          }else {
            $comments = $this->commentaryTable->findAllBy($params)->fetchAll();
          }
        }else{

          $idCom = null;

        }


        return $this->renderer->render('@blog/comments/layoutComments', compact('comments','idCom','idComSave','idPost','slugPost'));

  }

  public function createCom($idPost,$slugPost, ?string $idComSave = null)
  {
        $idUser = $this->session->get('auth.user');
        if($idUser) {
        return $this->renderer->render('@blog/comments/createComments', compact('idUser','idComSave','idPost','slugPost'));
        }
  }




}
