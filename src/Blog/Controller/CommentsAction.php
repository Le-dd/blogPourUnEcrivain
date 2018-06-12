<?php
namespace App\Blog\Controller;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use Framework\Validator;
use GuzzleHttp\Psr7\Response;
use App\Model\CommentaryTable;
use GuzzleHttp\Psr7\ResponseInterface;
use \Framework\Session\FlashService;
use Framework\Database\QueryHydrator;
use Framework\Actions\RouterAwareAction;
use \Framework\Email\SendMail;



class CommentsAction{
  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var mixed
   */
  private $table;

  /**
   * @var Router
   */
  private $router;

  /**
   * @var FlashService
   */
  private $flash;

  /**
   * @var string
   */
  private $viewPath = "@blog";

  /**
   * @var string
   */
  private $routePrefix = "blog.com";


  /**
   * @var string
   */
  private $comId ;

  /**
   * @var string
   */
  private $idPost;

  /**
   * @var string
   */
  private $slugPost;



  /**
   * @var array
   */
  protected $messages = [
    'create' => "Votre commentaire a bien été pris en compte",
  ];



  use RouterAwareAction;

  public function __construct(
    RendererInterface $renderer,
    Router $router,
    CommentaryTable $table,
    FlashService $flash

     ){
    $this->renderer = $renderer;
    $this->table = $table;
    $this->router = $router;
    $this->flash = $flash;

  }
   public function __invoke(Request $request)
  {

    $this->renderer->addGlobal('viewPath',$this->viewPath);
    $this->renderer->addGlobal('routePrefix',$this->routePrefix);
    $redirectPost = array_merge($request->getParsedBody());
    $this->idPost = $redirectPost['post_id'];
    $this->slugPost = $redirectPost['slugPost'];

    if(substr((string)$request->getUri(),-7) === 'comPost'){
      return $this->comPost($request);
    }


    return $this->redirect('blog.posts.show',[
          'slug'=> $this->slugPost,
          'id' => $this->idPost
      ]);

  }




  public function comPost(Request $request){

        if ($request->getMethod() === 'POST') {

          $params = $this->getParams($request);
          $params = $this->getNewParams($params);
          if($this->comId !== null){
            $params['comment_id'] = $this->comId;
          }
          $validator = $this->getValidators($params);
          if($validator->isValid()){

            $this->table->insert($params);
            $this->flash->success($this->messages['create']);
            return $this->redirect('blog.posts.show',[
                'slug'=> $this->slugPost,
                'id' => $this->idPost
              ]);
          }

          $errors = $validator->getErrors();

        }
        $paramsPath =[
            'slug'=> $this->slugPost,
            'id' => $this->idPost,
          ];
        $paramsPath = array_merge($paramsPath, compact('errors'));


        return $this->redirect('blog.posts.show',$paramsPath);

      }



      private function getParams (Request $request){
        $params = array_merge($request->getParsedBody());
        if(!empty($params['comment_id'])){
        $this->comId = $params['comment_id'];
      }

        return array_filter($params, function ($key) {
          return in_array($key, ['text', 'post_id', 'user_id']);
        }, ARRAY_FILTER_USE_KEY);
      }

      private function getValidators(array $params){

        return (new Validator($params))
          ->required('text', 'createdate', 'post_id', 'user_id')
          ->length('text',5);


      }

      private function getNewParams($params){

        return array_merge($params,[
          'createdate'=>  date("Y-m-d H:i:s")

        ]);
    }


}
