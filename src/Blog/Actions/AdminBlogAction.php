<?php
namespace App\Blog\Actions;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use Framework\Validator;
use GuzzleHttp\Psr7\Response;
use Framework\Actions\RouterAwareAction;
use App\Blog\Table\PostTable;
use \Framework\Session\FlashService;



class AdminBlogAction{
  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var PostTable
   */
  private $postTable;

  /**
   * @var Router
   */
  private $router;

  /**
   * @var FlashService
   */
  private $flash;



  use RouterAwareAction;

  public function __construct(
    RendererInterface $renderer,
    Router $router,
    PostTable $postTable,
    FlashService $flash

     ){
    $this->renderer = $renderer;
    $this->postTable = $postTable;
    $this->router = $router;
    $this->flash = $flash;

  }
   public function __invoke(Request $request)
  {
    if($request->getMethod() === 'DELETE'){
      return $this->delete($request);

    }
    if(substr((string)$request->getUri(),-3) === 'new'){
      return $this->create($request);
    }
    if($request->getAttribute('id')){
      return $this->edit($request);

    }

    return $this->index($request);

  }

  public function index(Request $request)
  {
    $params = $request->getQueryParams();
    $items = $this->postTable->findPaginated(6, $params['p'] ?? 1);


    return $this->renderer->render('@blog/admin/index', compact('items', 'session'));
  }



/**
 * Edite un article
 * @param  Request $request
 * @return ResponseInterface|string
 */
  public function edit(Request $request)
  {
    $item = $this->postTable->find($request->getAttribute('id'));

    if ($request->getMethod() === 'POST') {

      $params = $this->getParams($request);
      $validator =$this->getValidators($request);
      if($validator->isValid()){

        $this->postTable->update($item->id, $params);
        $this->flash->success('L\'article a bien été modifié');
        return $this->redirect('blog.admin.index');

      }
      $errors =$validator->getErrors();
      $params['id'] = $item->id;
      $item = $params;


    }
    return $this->renderer->render('@blog/admin/edit', compact('item','errors'));
  }


  /**
   * Crée un nouvel article
   * @param  Request $request
   * @return ResponseInterface|string
   */
  public function create(Request $request){

    if ($request->getMethod() === 'POST') {

      $params = $this->getParams($request);
      $params = array_merge($params,[
        'latitude'=> '61.218968',
        'longitude' => '-149.479427',
        'visible'=> '1',
        'location_id'=> '1',
        'name_place'=> 'gyhgygy'
      ]);
      $validator =$this->getValidators($request);
      if($validator->isValid()){

        $this->postTable->insert($params);
        $this->flash->success('L\'article a bien été modifié');
        return $this->redirect('blog.admin.index');
      }
      $item = $params;
      $errors = $validator->getErrors();


    }
    return $this->renderer->render('@blog/admin/create', compact('item','errors'));

  }

  /**
   * Supprime un article
   * @param  Request $request
   * @return ResponseInterface|string
   */
    public function delete(Request $request){

      $this->postTable->delete($request->getAttribute('id'));
      return $this->redirect('blog.admin.index');
    }


  private function getParams (Request $request){
    return array_filter($request->getParsedBody(), function ($key) {
      return in_array($key, ['title','slug','main','date','time',]);
    }, ARRAY_FILTER_USE_KEY);
  }

  private function getValidators(Request $request){

    return (new Validator($request->getParsedBody()))
      ->required('title','slug','main')
      ->length('main',10)
      ->length('title',2,250)
      ->length('slug',2,50)
      ->slug('slug');
  }

}
