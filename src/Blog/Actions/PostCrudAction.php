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
use \Framework\Session\FlashService;




class PostCrudAction extends CrudAction {

  /**
   * @var string
   */
  protected $viewPath = "@blog/admin/posts";

  /**
   * @var string
   */
  protected $routePrefix = "blog.admin";


  public function __construct(
    RendererInterface $renderer,
    Router $router,
    PostTable $table,
    FlashService $flash

     ){
       parent::__construct( $renderer,$router,$table,$flash);

  }


  protected function getParams (Request $request){
    return array_filter($request->getParsedBody(), function ($key) {
      return in_array($key, ['title','slug','main','date','time']);
    }, ARRAY_FILTER_USE_KEY);
  }

  protected function getValidators(Request $request){

    return parent::getValidators($request)
      ->required('title','slug','main','date','time')
      ->length('main',10)
      ->length('title',2,250)
      ->length('slug',2,50)
      ->date('date')
      ->time('time')
      ->slug('slug');
  }

  protected function getNewParams($params){

    return array_merge($params,[
      'latitude'=> '61.218968',
      'longitude' => '-149.479427',
      'visible'=> '1',
      'location_id'=> '1',
      'name_place'=> 'gyhgygy'
    ]);
}
}
