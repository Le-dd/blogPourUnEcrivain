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

  /**
   * @var RendererInterface
   */
  private $renderer;

  public function __construct(
    RendererInterface $renderer,
    Router $router,
    CommentaryTable $table,
    ReportTable $reportTable,
    FlashService $flash

     ){
       parent::__construct( $renderer,$router,$table,$flash);
       $this->reportTable = $reportTable;
       $this->renderer = $renderer;

  }
  /**
   * Supprime un élément
   * @param  Request $request
   * @return ResponseInterface|string
   */
    public function delete(Request $request){

      $this->table->delete($request->getAttribute('id'));
      $this->table->deleteBy($request->getAttribute('id'),'commentary','comment_id');
      $this->reportTable->deleteBy($request->getAttribute('id'),'report','comment_id');
      return $this->redirect($this->routePrefix .'.index');
    }


    /**
     * Edite un  élément
     * @param  Request $request
     * @return ResponseInterface|string
     */
      public function edit(Request $request)
      {
        $item = $this->table->find($request->getAttribute('id'));
        $params['commentId'] = $item->id;
        $users= $this->reportTable->findUserSign($params)->paginate(6, $params['p'] ?? 1);

        return $this->renderer->render(
          $this->viewPath .'/edit',
          $this->formParams(compact('item','errors','users'))
        );
      }




  protected function formParams(array $params): array
  {
    //$params['locations'] = $this->categoryTable->findList();
    return $params;

  }

  protected function getParams (Request $request){
    return array_filter($request->getParsedBody(), function ($key) {
      return in_array($key, ['id','comment_id','text', 'createdate', 'post_id', 'user_id']);
    }, ARRAY_FILTER_USE_KEY);
  }


  protected function getValidators(Request $request){

    return parent::getValidators($request)
      ->required('id','comment_id','text', 'createdate', 'post_id', 'user_id');
        }



}
