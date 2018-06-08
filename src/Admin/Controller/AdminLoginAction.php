<?php
namespace App\Admin\Controller;


use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use Framework\Validator;
use GuzzleHttp\Psr7\Response;
use App\Model\UserTable;
use App\Auth\ModelAuth\DatabaseAuth;
use GuzzleHttp\Psr7\ResponseInterface;
use \Framework\Session\FlashService;
use Framework\Database\QueryHydrator;
use Framework\Actions\RouterAwareAction;
use \Framework\Email\SendMail;
use Framework\Session\SessionInterface;



class AdminLoginAction{
  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var mixed
   */
  private $table;

  /**
   * @var mixed
   */
  private $auth;

  /**
   * @var Router
   */
  private $router;

  /**
   * @var SessionInterface
   */
  private $session;

  /**
   * @var string
   */
  private $viewPath = "@admin";

  /**
   * @var string
   */
  private $routePrefix = "admin.login";


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
    'editMail' => "Votre email bien été changé",
    'editPass' => "Votre mots de passe à bien été changé",
    'editLog' => "Votre nom d'utilisteur à bien été changé",
    'errorCookie' => "le cookie a disparu merci de vous réenregistrer",
    'errorPass' => "Mot de passe invalide merci de recommencer",
    'errorValid' => "Un élément est invalide merci de vous réenregistrer "
  ];



  use RouterAwareAction;

  public function __construct(
    RendererInterface $renderer,
    Router $router,
    UserTable $table,
    DatabaseAuth $auth,
    SessionInterface $session

     ){
    $this->renderer = $renderer;
    $this->table = $table;
    $this->router = $router;
    $this->session = $session;
    $this->auth = $auth;
  }
   public function __invoke(Request $request)
  {
    $this->renderer->addGlobal('viewPath',$this->viewPath);
    $this->renderer->addGlobal('routePrefix',$this->routePrefix);

    if(substr((string)$request->getUri(),-8) === 'editMail'){
      return $this->editMail($request);
    }
    if(substr((string)$request->getUri(),-9) === 'validMail'){
      return $this->validMail($request);
    }
    if(substr((string)$request->getUri(),-8) === 'editPass'){
      return $this->editPass($request);
    }
    if(substr((string)$request->getUri(),-7) === 'editLog'){
      return $this->editLog($request);
    }

    return $this->renderer->render(
      $this->viewPath .'/login/loginAdmin'
     );

  }


    /**
     * edit l'email de l'utilisateur
     * @param  Request $request
     * @return ResponseInterface|string
     */
    public function editMail(Request $request){


      if ($request->getMethod() === 'POST') {

        $params = $this->getParamsMail($request);
        $validator =$this->getValidatorsMail($request);
        if($validator->isValid()){

          setcookie("email", $params['email'], time() + (24*60*60), '/', null, false, true);

          (new SendMail($this->renderer))
            ->receiver($params['email'])
            ->header('Billet-simple-pour-alaska','serveur@bspalaska.com')
            ->subject('changement d\'email billet simple pour l\'alaska')
            ->message($this->viewPath .'/login/mail/validMail')
            ->send();

          (new FlashService($this->session))->success($this->messages['validMail']);
          return $this->renderer->render($this->viewPath .'/login/loginAdmin');
        }
        $errors = $validator->getErrors();

      }

      return $this->renderer->render(
        $this->viewPath .'/login/loginAdmin',
        $this->formParams(compact('errors'))
       );

    }

    /**
     * Validation du mail
     * @param  Request $request
     * @return ResponseInterface|string
     */
    public function validMail(Request $request){


        if(isset($_COOKIE['email'] ))
        {
          $id =$this->session->get('auth.user');
          $params=(array)$this->table->findBy('id', $id);

            $params = $this->transformParams($params);
            $params['email'] = $_COOKIE['email'];
            $validator =$this->getCookieValidators($params);
            if($validator->isValid()){
              setcookie("email","", time() - ((24*60*60)+1), '/', null, false, true);
              $this->table->update($params['id'], $params);
              (new FlashService($this->session))->success($this->messages['editMail']);
              return $this->renderer->render(
                $this->viewPath .'/login/loginAdmin'
               );
            }else{

              (new FlashService($this->session))->error($this->messages['errorValid']);
              return $this->renderer->render(
                $this->viewPath .'/login/loginAdmin'
               );
            }

          $errors = $validpassword ->getErrors();
        }else{
          (new FlashService($this->session))->error($this->messages['errorCookie']);
        }

      return $this->renderer->render(
        $this->viewPath .'/login/loginAdmin',
        $this->formParams(compact('errors'))
       );
    }



    /**
     * Edit le mot de passe de l'utilisateur
     * @param  Request $request
     * @return ResponseInterface|string
     */
    public function editPass(Request $request){


      if ($request->getMethod() === 'POST') {

        $params = $this->getParamsPass($request);
        $validator =$this->getValidatorsPass($params);
        if($validator->isValid())
        {

          $user = $this->auth->login($params['login'],$params['ancienPassword']);

          if($user){

            $user = $this->transformParams((array)$user);
            $user['password'] = password_hash($params['password'], PASSWORD_DEFAULT);

            $this->table->update($user['id'], $user);
            (new FlashService($this->session))->success($this->messages['editPass']);
            return $this->renderer->render(
              $this->viewPath .'/login/loginAdmin'
             );

          }
          (new FlashService($this->session))->error($this->messages['errorPass']);

        }
        $errors = $validator->getErrors();

      }

      return $this->renderer->render(
        $this->viewPath .'/login/loginAdmin',
        $this->formParams(compact('errors'))
       );

    }

    /**
     * Edit le mot de passe de l'utilisateur
     * @param  Request $request
     * @return ResponseInterface|string
     */
    public function editLog(Request $request){


      if ($request->getMethod() === 'POST') {

        $params = $this->getParamsLog($request);
        if($params)
        $validator =$this->getValidatorsLog($params);
        if($validator->isValid())
        {

          $user = $this->auth->login($params['ancienLogin'],$params['passwordLog']);

          if($user){

            $user = $this->transformParams((array)$user);
            $user['login'] = $params['login2'];

            $this->table->update($user['id'], $user);
            (new FlashService($this->session))->success($this->messages['editLog']);
            return $this->renderer->render(
              $this->viewPath .'/login/loginAdmin'
             );

          }
          (new FlashService($this->session))->error($this->messages['errorPass']);

        }
        $errors = $validator->getErrors();

      }

      return $this->renderer->render(
        $this->viewPath .'/login/loginAdmin',
        $this->formParams(compact('errors'))
       );

    }



    private function getParamsLog(Request $request){
      $params = array_merge($request->getParsedBody());
      if(!empty($params['veriflogin'])){
      $this->verifPassword = $params['veriflogin'];
    }
          return array_filter($params, function ($key) {
        return in_array($key, [ 'login2','ancienLogin','passwordLog']);
      }, ARRAY_FILTER_USE_KEY);
    }


    private function getParamsPass(Request $request){
      $params = array_merge($request->getParsedBody());
      if(!empty($params['verifpassword'])){
      $this->verifPassword = $params['verifpassword'];
    }
          return array_filter($params, function ($key) {
        return in_array($key, [ 'password','login','ancienPassword']);
      }, ARRAY_FILTER_USE_KEY);
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
          return in_array($key, ['email']);
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
        ->unique('email','email', $this->table->getTable(),$this->table->getPdo())
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
        ->unique('email','email', $this->table->getTable(),$this->table->getPdo());
    }

    private function getValidatorsPass(array $params){

      return (new Validator($params))
        ->required('password','login','ancienPassword' )
        ->isEqual('password',$this->verifPassword);

    }
    private function getValidatorsLog(array $params){

      return (new Validator($params))
        ->required('login2','ancienLogin','passwordLog')
        ->isEqual('login2',$this->verifPassword);

    }




  }
