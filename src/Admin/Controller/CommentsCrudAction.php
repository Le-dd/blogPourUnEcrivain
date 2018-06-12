<?php
namespace App\Admin\Controller;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use Framework\Actions\CrudAction;
use App\Model\CommentaryTable;
use App\Model\ReportTable;
use Framework\Session\FlashService;




class CommentsCrudAction extends CrudAction {

  /**
   * @var string
   */
  protected $viewPath = "@admin/comments";

  /**
   * @var string
   */
  protected $routePrefix = "blog.com.admin";

  /**
   * @var ReportTable
   */
  private $reportTable;


  public function __construct(
    RendererInterface $renderer,
    Router $router,
    CommentaryTable $table,
    ReportTable $reportTable,
    FlashService $flash

     ){
       parent::__construct( $renderer,$router,$table,$flash);
       $this->reportTable = $reportTable;

  }


  protected function formParams(array $params): array
  {
    $params['locations'] = $this->categoryTable->findList();
    return $params;

  }

  protected function getParams (Request $request){
    return array_filter($request->getParsedBody(), function ($key) {
      return in_array($key, ['latitude','longitude','name_locality']);
    }, ARRAY_FILTER_USE_KEY);
  }


  protected function getValidators(Request $request){

    return parent::getValidators($request)
      ->required('latitude','longitude','name_locality')
      ->length('name_locality',2,250);
  }



}
