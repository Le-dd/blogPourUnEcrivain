<?php
namespace Framework\Actions;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use Framework\Validator;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseInterface;
use \Framework\Session\FlashService;



class CrudAction{
  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var mixed
   */
  protected $table;

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
  protected $viewPath;

  /**
   * @var string
   */
  protected $routePrefix;

  /**
   * @var array
   */
  protected $messages = [
    'create' => "L'élément a bien été créé",
    'edit' => "L'élément a bien été modifié"
  ];



  use RouterAwareAction;

  public function __construct(
    RendererInterface $renderer,
    Router $router,
    $table,
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

/**
 * Affiche la liste des éléments
 * @param  Request $request
 * @return string
 */
  public function index(Request $request)
  {
    $params = $request->getQueryParams();
    $items = $this->table->findPaginated(6, $params['p'] ?? 1);


    return $this->renderer->render(
      $this->viewPath .'/index',
      compact('items','errors')
   );
  }



/**
 * Edite un  élément
 * @param  Request $request
 * @return ResponseInterface|string
 */
  public function edit(Request $request)
  {
    $item = $this->table->find($request->getAttribute('id'));

    if ($request->getMethod() === 'POST') {


      $validator =$this->getValidators($request);
      if($validator->isValid()){

        $this->table->update($item->id, $this->getParams($request));
        $this->flash->success($this->messages['edit']);
        return $this->redirect($this->routePrefix .'.index');

      }
      $errors =$validator->getErrors();
      $params = $request->getParsedBody();
      $params['id'] = $item->id;
      $item = $params;
    }
    return $this->renderer->render(
      $this->viewPath .'/edit',
      $this->formParams(compact('item','errors'))
    );
  }


  /**
   * Crée un nouvel élément
   * @param  Request $request
   * @return ResponseInterface|string
   */
  public function create(Request $request){

    $item = $this->getNewEntity();
    if ($request->getMethod() === 'POST') {

      $params = $this->getParams($request);
      $params = $this->getNewParams($params);
      $validator =$this->getValidators($request);
      if($validator->isValid()){

        $this->table->insert($params);
        $this->flash->success($this->messages['create']);
        return $this->redirect($this->routePrefix .'.index');
      }
      $item = $params;
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

      $this->table->delete($request->getAttribute('id'));
      return $this->redirect($this->routePrefix .'.index');
    }


  protected function getParams (Request $request){
    return array_filter($request->getParsedBody(), function ($key) {
      return in_array($key, []);
    }, ARRAY_FILTER_USE_KEY);
  }

  protected function getValidators(Request $request){

    return (new Validator(array_merge($request->getParsedBody(),$request->getUploadedFiles())));

  }
  protected function getNewEntity(){

    return [];

  }
  protected function getNewParams($params){

    return $params;

  }

  protected function formParams(array $params): array
  {

    return $params;

  }

}
