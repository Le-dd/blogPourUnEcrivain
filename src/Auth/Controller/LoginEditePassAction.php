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



class LoginEditePassAction{
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
    'edit' => "Votre mot de passe a bien été modifier vous pouvez vous connecter maintenant",
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

    if(substr((string)$request->getUri(),-4) === 'edit'){
      return $this->edit($request);
    }
    if(substr((string)$request->getUri(),-10) === 'validEpass'){
      return $this->validEpass($request);
    }


    return $this->redirect($this->routePrefix);

  }



  /**
   * Crée un nouvel élément
   * @param  Request $request
   * @return ResponseInterface|string
   */
  public function edit(Request $request){


    if ($request->getMethod() === 'POST') {

      $params = $this->getParamsMail($request);
      $validator =$this->getValidatorsMail($request);
      if($validator->isValid()){
        $params=$this->table->findBy('email', $params['email']);
        setcookie("params", json_encode($params), time() + (24*60*60), '/', null, false, true);

        (new SendMail($this->renderer))
          ->receiver($params->email)
          ->header('Billet-simple-pour-alaska','serveur@bspalaska.com')
          ->subject('Mot de passe oublie billet simple pour l\'alaska')
          ->message($this->viewPath .'/mail/validEditePass')
          ->send();

        $this->flash->success($this->messages['validMail']);
        return $this->renderer->render($this->viewPath .'/blog/editPass');
      }
      $errors = $validator->getErrors();


    }

    return $this->renderer->render(
      $this->viewPath .'/blog/editPass',
      $this->formParams(compact('errors'))
     );

  }

  /**
   * Validation du mail
   * @param  Request $request
   * @return ResponseInterface|string
   */
  public function validEpass(Request $request){



    if ($request->getMethod() === 'POST')
    {
      if(isset($_COOKIE['params'] ) || !empty($this->valCookie))
      {
        $params =$this->transformParams(json_decode($_COOKIE['params'],true));
        $newpassword = $this->getParams($request);
        $validpassword = $this->getPassValidators($newpassword);
        if($validpassword ->isValid())
        {
          $params['password'] = password_hash($newpassword['password'], PASSWORD_DEFAULT);
          $validator =$this->getCookieValidators($params);
          if($validator->isValid()){
            setcookie("params", "", time() - ((24*60*60)+1), '/', null, false, true);
            $this->table->update($params['id'], $params);
            $this->flash->success($this->messages['edit']);
            return $this->renderer->render(
              $this->viewPath .'/valide/validEpass'
             );
          }else{
            $errors = json_encode($validator->getErrors());
            $this->flash->error($this->messages['errorValid']. $errors );
            return $this->renderer->render(
              $this->viewPath .'/valide/validEpass'
             );
          }
        }
        $errors = $validpassword ->getErrors();
      }else{
        $this->flash->error($this->messages['errorCookie']);
      }
    }
    return $this->renderer->render(
      $this->viewPath .'/valide/validEpass',
      $this->formParams(compact('errors'))
     );
  }



    private function getParams (Request $request){
      $params = array_merge($request->getParsedBody());
      if(!empty($params['verifpassword'])){
      $this->verifPassword = $params['verifpassword'];
    }

      return array_filter($params, function ($key) {
        return in_array($key, [ 'password' ]);
      }, ARRAY_FILTER_USE_KEY);
    }

    private function getParamsMail (Request $request){
      $params = array_merge($request->getParsedBody());
      return array_filter($params, function ($key) {
        return in_array($key, ['email' ]);
      }, ARRAY_FILTER_USE_KEY);
    }

    private function transformParams(array $params){
      $arrayParams=[];
      foreach ($params as $key => $value) {
        if($key === "permissionId"){
          $key = 'permission_id';
        }
        if($key === "createDate"){
          $key = 'create_date';
        }
        if($key === "lastAuth"){
          $key = 'last_auth';
        }
        $arrayParams[$key]= $value;
      }

      return $arrayParams;
    }


  private function formParams(array $params): array
  {

    return $params;

  }

  private function getCookieValidators(array $params){

    return (new Validator($params))
      ->required('id','login', 'password', 'email','permission_id','create_date','last_auth' )
      ->length('login',4)
      ->length('email',2,50)
      ->length('permission_id',1,20)
      ->mail('email')
      ->date('create_date')
      ->date('last_auth')
      ->exists('email','email', $this->table->getTable(),$this->table->getPdo())
      ->exists('login','login', $this->table->getTable(),$this->table->getPdo());


  }

  private function getPassValidators(array $params){

    return (new Validator($params))
      ->required( 'password' )
      ->isEqual('password',$this->verifPassword);

  }

  private function getValidatorsMail(Request $request){

    return (new Validator(array_merge($request->getParsedBody())))
      ->required('email' )
      ->mail('email')
      ->exists('email','email', $this->table->getTable(),$this->table->getPdo());


  }


}
