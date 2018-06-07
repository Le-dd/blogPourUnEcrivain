<?php
namespace App\Auth\Controller;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use Framework\Validator;
use GuzzleHttp\Psr7\Response;
use App\Model\UserTable;
use GuzzleHttp\Psr7\ResponseInterface;
use \Framework\Session\FlashService;
use Framework\Database\QueryHydrator;
use Framework\Actions\RouterAwareAction;



class LoginCrudAction{
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
  protected $viewPath = "@auth";

  /**
   * @var string
   */
  protected $routePrefix = "auth.crud";

  /**
   * @var array
   */
  protected $messages = [
    'create' => "Votre compte utilisateur a bien été crée vous pouvez vous connecter maintenant",
    'edit' => "L'élément a bien été modifié"
  ];



  use RouterAwareAction;

  public function __construct(
    RendererInterface $renderer,
    Router $router,
    UserTable $table,
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

    return $this->redirect($this->routePrefix);

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

    }
    return $this->renderer->render(
      $this->viewPath .'/edit',
      $this->formParams(compact('errors'))
    );
  }


  /**
   * Crée un nouvel élément
   * @param  Request $request
   * @return ResponseInterface|string
   */
  public function create(Request $request){


    if ($request->getMethod() === 'POST') {

      $params = $this->getParams($request);
      $params = $this->getNewParams($params);
      $validator =$this->getValidators($request);
      var_dump($params);die;
      if($validator->isValid()){

        setcookie('Params', $params, time() + 365*24*3600, null, null, false, true);
        $this->flash->success($this->messages['create']);
        return $this->redirect($this->routePrefix .'.index');
      }
      $errors = $validator->getErrors();


    }

    return $this->renderer->render(
      $this->viewPath .'/create',
      $this->formParams(compact('errors'))
     );

  }


  public function valideCreate(Request $request){


    if ($request->getMethod() === 'POST') {

      $params = $this->getParams($request);
      $params = $this->getNewParams($params);
      $validator =$this->getValidators($request);
      var_dump($params);die;
      if($validator->isValid()){

        $this->table->insert($params);
        $this->flash->success($this->messages['create']);
        return $this->redirect($this->routePrefix .'.index');
      }
      $errors = $validator->getErrors();


    }

    return $this->renderer->render(
      $this->viewPath .'/create',
      $this->formParams(compact('errors'))
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
      $params = array_merge($request->getParsedBody());
      return array_filter($params, function ($key) {
        return in_array($key, ['login', 'password', 'mail' ]);
      }, ARRAY_FILTER_USE_KEY);
    }



  private function getNewParams($params){

    return array_merge($params,[
      'permission_id'=> '2',
      'create_date' => date("Y-m-d"),
      'last_auth'=> date("Y-m-d")
    ]);
}


  protected function formParams(array $params): array
  {

    return $params;

  }

  protected function formParamsAdmin(array $params): array
  {
    $params['locations'] = $this->categoryTable->findList();
    return $params;

  }




  protected function getValidators(Request $request){

    return (new Validator(array_merge($request->getParsedBody())))
      ->required('login', 'password', 'mail' )
      ->length('login',4)
      ->length('password',7)
      ->length('mail',2,50);

  }


}
