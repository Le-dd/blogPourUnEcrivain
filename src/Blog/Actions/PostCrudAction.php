<?php
namespace App\Blog\Actions;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use \Framework\Actions\CrudAction;
use App\Blog\Table\PostTable;
use App\Blog\Table\CategoryTable;
use \Framework\Session\FlashService;
use App\Blog\PostUpload;




class PostCrudAction extends CrudAction {

  /**
   * @var string
   */
  protected $viewPath = "@blog/admin/posts";

  /**
   * @var string
   */
  protected $routePrefix = "blog.admin";

  /**
   * @var array
   */
  private $categoryTable;
  /**
   * @var PostUpload
   */
  private $postUpload;


  public function __construct(
    RendererInterface $renderer,
    Router $router,
    PostTable $table,
    FlashService $flash,
    CategoryTable $categoryTable,
    PostUpload $postUpload

     ){
       parent::__construct( $renderer,$router,$table,$flash);
       $this->categoryTable = $categoryTable;
       $this->postUpload = $postUpload;

  }



  protected function formParams(array $params): array
  {
    $params['locations'] = $this->categoryTable->findList();
    return $params;

  }


  protected function getParams (Request $request){
    $params = array_merge($request->getParsedBody(),$request->getUploadedFiles());
    return array_filter($params, function ($key) {
      return in_array($key, ['title','slug','main','date','time','location_id','visible']);
    }, ARRAY_FILTER_USE_KEY);
  }

  protected function getValidators(Request $request){

    return parent::getValidators($request)
      ->required('title','slug','main','date','time','location_id')
      ->length('main',10)
      ->length('title',2,250)
      ->length('slug',2,50)
      ->exists('location_id', $this->categoryTable->getTable(),$this->categoryTable->getPdo())
      ->date('date')
      ->time('time')
      ->slug('slug');
  }

  protected function getNewParams($params){

    return array_merge($params,[
      'latitude'=> '61.218968',
      'longitude' => '-149.479427',
      'name_place'=> 'gyhgygy'
    ]);
}
}
