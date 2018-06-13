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


  public function __construct(
    RendererInterface $renderer,
    Router $router,
    ImageTable $table,
    FlashService $flash,
    CategoryTable $categoryTable,
    PostUpload $postUpload

     ){
       parent::__construct( $renderer,$router,$table,$flash);
       $this->renderer = $renderer;
       $this->categoryTable = $categoryTable;
       $this->postUpload = $postUpload;

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

        $this->table->insert($this->getParams($request),null);
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



  protected function formParams(array $params): array
  {
    //$params['locations'] = $this->categoryTable->findList();
    return $params;

  }


  protected function getParams (Request $request,$item){
    $params = array_merge($request->getParsedBody(),$request->getUploadedFiles());
    if(!is_null($item)){$item = $item->url;}
    $params['url'] = $this->postUpload->upload($params['url'],$item);
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
