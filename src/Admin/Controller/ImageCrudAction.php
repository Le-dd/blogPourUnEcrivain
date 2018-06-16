<?php
namespace App\Admin\Controller;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use Framework\Actions\CrudAction;
use App\Model\ImageTable;
use App\Model\CategoryTable;
use Framework\Session\FlashService;
use App\Admin\Upload\PostUpload;
use Framework\Database\QueryHydrator;
use Framework\Session\SessionInterface;




class ImageCrudAction extends CrudAction {

  /**
   * @var string
   */
  protected $viewPath = "@admin/image";

  /**
   * @var string
   */
  protected $routePrefix = "blog.img.admin";

  /**
   * @var array
   */
  private $categoryTable;
  /**
   * @var PostUpload
   */
  private $postUpload;

  /**
   * @var RendererInterface
   */
  private $renderer;

  /**
   * @var SessionInterface
   */
  private $session;


  public function __construct(
    RendererInterface $renderer,
    Router $router,
    ImageTable $table,
    FlashService $flash,
    CategoryTable $categoryTable,
    PostUpload $postUpload,
    SessionInterface $session

     ){
       parent::__construct( $renderer,$router,$table,$flash);
       $this->renderer = $renderer;
       $this->categoryTable = $categoryTable;
       $this->postUpload = $postUpload;
       $this->session = $session;

  }

  public function __invoke(Request $request)
 {
   $this->renderer->addGlobal('viewPath',$this->viewPath);
   $this->renderer->addGlobal('routePrefix',$this->routePrefix);
   if($request->getMethod() === 'DELETE'){
     return $this->delete($request);

   }
   if(substr((string)$request->getUri(),-10) === 'postImages'){
     return $this->indexImage($request);
   }
   if(substr((string)$request->getUri(),-3) === 'new'){
     return $this->create($request);
   }
   if($request->getAttribute('id')){
     return $this->edit($request);

   }

   return $this->index($request);

 }
 /**
  * Affiche la liste des éléments
  * @param  Request $request
  * @return string
  */
   public function index(Request $request)
   {
     $params = $request->getQueryParams();

     $items = $this->table->findAll()->paginate(6, $params['p'] ?? 1);

     return $this->renderer->render(
       $this->viewPath .'/index',
       $this->formParams(compact('items','errors'))
    );
   }

 /**
 * Affiche la liste des éléments
 * @param  Request $request
 * @return string
 */
 public function indexImage(Request $request)
 {
   $params = $request->getQueryParams();
   $post= $request->getParsedBody();
   $this->session->set('postInfo',$post);
   $items = $this->table->findAll()->paginate(6, $params['p'] ?? 1);
   return $this->renderer->render(
     $this->viewPath .'/index',
     compact('items','errors','post')
  );
 }

  /**
   * Crée un nouvel élément
   * @param  Request $request
   * @return ResponseInterface|string
   */
  public function create(Request $request){


    if ($request->getMethod() === 'POST') {


      $validator = $this->getValidators($request);

      if($validator->isValid()){

        $this->table->insert($this->getParams($request));
        $this->flash->success($this->messages['create']);
        return $this->redirect($this->routePrefix .'.index');
      }

      $item = $request->getParsedBody();

      $errors = $validator->getErrors();


    }

    return $this->renderer->render(
      $this->viewPath .'/create',
      $this->formParams(compact('item','errors'))
     );

  }

  /**
   * Supprime un élément
   * @param  Request $request
   * @return ResponseInterface|string
   */
    public function delete(Request $request){

      $image = $this->table->find($request->getAttribute('id'));
      $this->postUpload->delete($image->url);
      return parent::delete($request);
    }




  protected function formParams(array $params): array
  {

    $post = $this->session->get('postInfo');
    if($post){
      $params['post'] = $post;
    }

    return $params;

  }


  protected function getParams (Request $request,$item = null){
    $params = array_merge($request->getParsedBody(),$request->getUploadedFiles());
    if(!is_null($item)){$item = $item->url;}
    var_dump($item);
    var_dump($params);

    $params['url'] = $this->postUpload->upload($params['url'],$item);
  if(is_null($params['url'])){
    $params['url'] = $item;
  }

    return array_filter($params, function ($key) {
      return in_array($key, ['title','alt','url']);
    }, ARRAY_FILTER_USE_KEY);
  }



  protected function getValidators(Request $request){

    $validator = parent::getValidators($request)
      ->required('title','alt','url')
      ->length('alt',10)
      ->length('title',2,250)
      ->extension('url',['jpg','png']);
      if (is_null($request->getAttribute('id'))) {
      $validator->uploaded('url');
      }
      return $validator;

  }


}
