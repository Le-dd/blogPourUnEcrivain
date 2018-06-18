<?php
namespace App\Admin\Controller;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use App\Model\FirstPageTable;
use App\Model\CategoryTable;
use App\Model\ImagePostTable;
use App\Model\ImageTable;
use Framework\Session\FlashService;
use App\Admin\Upload\PostUpload;
use Framework\Session\SessionInterface;
use Framework\Validator;




class IndexCrudAction {

  /**
   * @var Router
   */
  private $router;

  /**
   * @var FlashService
   */
  private $flash;

  /**
   * @var FirstPageTable
   */
  private $table;

  /**
   * @var string
   */
  private $viewPath = "@admin/index";

  /**
   * @var string
   */
  private $routePrefix = "blog.index.admin";

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

  /**
   * @var string
   */
  private $createImage;



  public function __construct(
    RendererInterface $renderer,
    Router $router,
    FirstPageTable $table,
    FlashService $flash,
    CategoryTable $categoryTable,
    PostUpload $postUpload,
    ImagePostTable $imagePostTable,
    ImageTable $imageTable,
    SessionInterface $session
     ){

       $this->categoryTable = $categoryTable;
       $this->postUpload = $postUpload;
       $this->imagePostTable = $imagePostTable;
       $this->imageTable = $imageTable;
       $this->session = $session;
       $this->renderer = $renderer;
       $this->router = $router;
       $this->flash = $flash;
       $this->table = $table;

  }

  public function __invoke(Request $request)
 {
   $this->renderer->addGlobal('viewPath',$this->viewPath);
   $this->renderer->addGlobal('routePrefix',$this->routePrefix);
   if($request->getMethod() === 'DELETE'){
     return $this->delete($request);

   }
   if(substr((string)$request->getUri(),-5) === 'image'){
     return $this->image($request);
   }

   if(substr((string)$request->getUri(),-4) === 'edit'){
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

     $item = $this->table->find("1");

     $image1 =$this->imageTable->find($item->imgFond);
     $image2 = $this->imageTable->find($item->imgProfil);

     return $this->renderer->render(
       $this->viewPath .'/crudIndex',
       compact('item','errors','image1','image2')
    );
   }



 public function image(Request $request)
 {
   $params = $request->getParsedBody();
   $item = $this->transformParams(json_decode($params['itemSave'],true));


   if ($request->getMethod() === 'POST') {

     if($params['PositionImg'] == 1){
       $item['imgFond'] = $params['idImg'];
       }
     if($params['PositionImg'] == 2){
       $item['imgProfil'] = $params['idImg'];
       }
     }



   $image1 = $this->imageTable->find($item['imgFond']);
   $image2 = $this->imageTable->find($item['imgProfil']);


   return $this->renderer->render(
     $this->viewPath .'/crudIndex',
     compact('item','errors','image1','image2')
  );

 }


 /**
  * Crée un nouvel élément
  * @param  Request $request
  * @return ResponseInterface|string
  */
 public function edit(Request $request){

   $item = [];
   if ($request->getMethod() === 'POST') {

     $params = $this->getParams($request,$item);

     $validator =$this->getValidators($params);
     if($validator->isValid()){
       $item = $this->transformParams($params);
       $this->table->update("1", $params);
       $image1 = $this->imageTable->find($item['imgFond']);
       $image2 = $this->imageTable->find($item['imgProfil']);
       $this->flash->success("L'élément a bien été éditer");

       return $this->renderer->render(
         $this->viewPath .'/crudIndex',
         compact('item','errors','image1','image2')
      );

     }

     $errors = $validator->getErrors();


   }

   $item = $this->transformParams($this->getParams($request,$item));

   $image1 = $this->imageTable->find($item['imgFond']);
   $image2 = $this->imageTable->find($item['imgProfil']);


   return $this->renderer->render(
     $this->viewPath .'/crudIndex',
     compact('item','errors','image1','image2')
  );


 }




  private function getParams (Request $request,$item){
    $params = array_merge($request->getParsedBody());

    return array_filter($params, function ($key) {
      return in_array($key, ['img_fond','title','subtitle','img_profil','presentation','main']);
    }, ARRAY_FILTER_USE_KEY);
  }

  private function getValidators(array $params){

    return (new Validator($params))
      ->required('img_fond','title','subtitle','img_profil','presentation','main')
      ->length('title',2,255)
      ->length('subtitle',2,255)
      ->length('presentation',10)
      ->length('main',10)
      ->exists('img_fond','id', $this->imageTable->getTable(),$this->imageTable->getPdo())
      ->exists('img_profil','id', $this->imageTable->getTable(),$this->imageTable->getPdo());
  }





private function transformParams(array $params){
  $arrayParams=[];
  foreach ($params as $key => $value) {
    if($key === "img_fond"){
      $key = 'imgFond';
    }
    if($key === "img_profil"){
      $key = 'imgProfil';
    }

    $arrayParams[$key]= $value;
  }

  return $arrayParams;
}


}
