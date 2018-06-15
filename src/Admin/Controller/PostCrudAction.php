<?php
namespace App\Admin\Controller;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use Framework\Actions\CrudAction;
use App\Model\PostTable;
use App\Model\CategoryTable;
use App\Model\ImagePostTable;
use App\Model\ImageTable;
use Framework\Session\FlashService;
use App\Admin\Upload\PostUpload;
use Framework\Session\SessionInterface;




class PostCrudAction extends CrudAction {

  /**
   * @var string
   */
  protected $viewPath = "@admin/posts";

  /**
   * @var string
   */
  protected $routePrefix = "blog.admin";

  /**
   * @var array
   */
  private $categoryTable;

  /**
   * @var ImagePostTable
   */
  private $imagePostTable;

  /**
   * @var ImageTable
   */
  private $imageTable;

  /**
   * @var PostUpload
   */
  private $postUpload;

  /**
   * @var SessionInterface
   */
  private $session;

  /**
   * @var RendererInterface
   */
  private $renderer;

  public function __construct(
    RendererInterface $renderer,
    Router $router,
    PostTable $table,
    FlashService $flash,
    CategoryTable $categoryTable,
    PostUpload $postUpload,
    ImagePostTable $imagePostTable,
    ImageTable $imageTable,
    SessionInterface $session
     ){
       parent::__construct( $renderer,$router,$table,$flash);
       $this->categoryTable = $categoryTable;
       $this->postUpload = $postUpload;
       $this->imagePostTable = $imagePostTable;
       $this->imageTable = $imageTable;
       $this->session = $session;
       $this->renderer = $renderer;

  }

  public function __invoke(Request $request)
 {
   $this->renderer->addGlobal('viewPath',$this->viewPath);
   $this->renderer->addGlobal('routePrefix',$this->routePrefix);
   if($request->getMethod() === 'DELETE'){
     return $this->delete($request);

   }
   if(substr((string)$request->getUri(),-6) === 'images'){
     return $this->postImage($request);
   }
   if(substr((string)$request->getUri(),-3) === 'new'){
     return $this->create($request);
   }
   if($request->getAttribute('id')){
     return $this->edit($request);

   }

   return $this->index($request);

 }

 public function postImage(Request $request)
 {
   $params = $request->getParsedBody();
   $item = $this->table->find($params['idPost']);

   if ($request->getMethod() === 'POST') {
     $paramsBY['postId']=$params['idPost'];
     $existe = $this->imagePostTable->findAllBy($paramsBY)->count();
     if($existe != 0){
       $idImage = $this->imagePostTable->findBY('post_id',$params['idPost']);
       $paramsImage["post_id"]=$params['idPost'];
       $paramsImage["image_id"]=$params['idImg'];
       $this->imagePostTable->update($idImage->id, $paramsImage);
       $this->session->delete('postInfo');

     }else{
       $paramsImage["post_id"]=$params['idPost'];
       $paramsImage["image_id"]=$params['idImg'];
       $this->imagePostTable->insert( $paramsImage);
       $this->session->delete('postInfo');
     }

   }

  $item = $this->table->find($params['idPost']);
   return $this->renderer->render(
     $this->viewPath .'/edit',
     $this->formParams(compact('item','errors'))
   );
 }

  protected function formParams(array $params): array
  {

    $image = $this->takeImage($params['item']->id);
    $params['locations'] = $this->categoryTable->findList();
    $params['itemSave'] = json_encode($params);
    $params['image'] = $image;

    return $params;

  }


  protected function getParams (Request $request,$item){
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
      ->exists('location_id','id', $this->categoryTable->getTable(),$this->categoryTable->getPdo())
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

private function takeImage($id){
  $params['postId']= $id;
  $existe = $this->imagePostTable->findAllBy($params)->count();
  if($existe !== 0){
    $idImage = $this->imagePostTable->findBY('post_id',$params['postId']);
    return $this->imageTable->find($idImage->imageId);
  }


  $result['url']='default.jpg';
  $result['alt']='image par default';
  return $result;

}
}
