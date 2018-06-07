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
use \Framework\Email\SendMail;



class LoginCreateAction{
  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var mixed
   */
  private $table;

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
  private $viewPath = "@auth";

  /**
   * @var string
   */
  private $routePrefix = "auth.crud";


  /**
   * @var string
   */
  private $verifPassword ;

  /**
   * @var string
   */
  private $verifHash ;

  /**
   * @var array
   */
  protected $messages = [
    'validMail' => "Un mail vous a été envoyer pour validé votre création de compte ",
    'create' => "Votre compte utilisateur a bien été crée vous pouvez vous connecter maintenant",
    'errorCookie' => "le cookie a disparu merci de vous réenregistrer",
    'errorValid' => "Un élément est invalide merci de vous réenregistrer "
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

    if(substr((string)$request->getUri(),-3) === 'new'){
      return $this->create($request);
    }
    if(substr((string)$request->getUri(),-12) === 'valideCreate'){
      return $this->valideCreate($request);
    }


    return $this->redirect($this->routePrefix);

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
      if($validator->isValid()){
        $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        $verifHash = password_hash($params['password'], PASSWORD_DEFAULT);
        setcookie("verifHash", $verifHash, time() + (1*24*60*60), '/', null, false, true);
        setcookie("params", json_encode($params), time() + (1*24*60*60), '/', null, false, true);

        (new SendMail($this->renderer))
          ->receiver($params['email'])
          ->header('Billet-simple-pour-alaska','serveur@bspalaska.com')
          ->subject('Validation d\'inscription au blog')
          ->message($this->viewPath .'/mail/validCreateUser')
          ->send();

        $this->flash->success($this->messages['validMail']);
        return $this->renderer->render($this->viewPath .'/blog/create');
      }
      $errors = $validator->getErrors();


    }

    return $this->renderer->render(
      $this->viewPath .'/blog/create',
      $this->formParams(compact('errors'))
     );

  }

  /**
   * Validation du mail
   * @param  Request $request
   * @return ResponseInterface|string
   */
  public function valideCreate(Request $request){


      if(isset($_COOKIE['verifHash'])){
        $this->verifHash = $_COOKIE['verifHash'];
      setcookie("verifHash", "", time() - ((1*24*60*60)+1), '/', null, false, true);
      }

      if(isset($_COOKIE['params'])){

        $params = json_decode($_COOKIE['params'],true);
        setcookie("params", "", time() - ((1*24*60*60)+1), '/', null, false, true);
        $validator =$this->getCookieValidators($params);
          if($validator->isValid()){
            $this->table->insert($params);
            $this->flash->success($this->messages['create']);
            return $this->renderer->render(
              $this->viewPath .'/valide/valideCreate'
             );
          }else{
            $errors = json_encode($validator->getErrors());
            $this->flash->error($this->messages['errorValid']. $errors );
            return $this->renderer->render(
              $this->viewPath .'/valide/valideCreate'
             );
          }
        }else{
          $this->flash->error($this->messages['errorCookie']);
        }



    return $this->renderer->render(
      $this->viewPath .'/valide/valideCreate'
     );

  }



    protected function getParams (Request $request){
      $params = array_merge($request->getParsedBody());
      if(!empty($params['verifpassword'])){
      $this->verifPassword = $params['verifpassword'];
    }
      return array_filter($params, function ($key) {
        return in_array($key, ['login', 'password', 'email' ]);
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

  protected function getCookieValidators(array $params){

    return (new Validator($params))
      ->required('login', 'password', 'email','permission_id','create_date','last_auth' )
      ->length('login',4)
      ->length('email',2,50)
      ->length('permission_id',1,20)
      ->ishash('password',$this->verifHash)
      ->mail('email')
      ->date('create_date')
      ->date('last_auth')
      ->unique('email','email', $this->table->getTable(),$this->table->getPdo())
      ->unique('login','login', $this->table->getTable(),$this->table->getPdo());


  }

  protected function getValidators(Request $request){

    return (new Validator(array_merge($request->getParsedBody())))
      ->required('login', 'password', 'email' )
      ->length('login',4)
      ->length('password',7)
      ->length('email',2,50)
      ->isEqual('password',$this->verifPassword)
      ->mail('email')
      ->unique('email','email', $this->table->getTable(),$this->table->getPdo())
      ->unique('login','login', $this->table->getTable(),$this->table->getPdo());


  }


}
