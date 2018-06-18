<?php
namespace App\Blog\Controller;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Framework\Validator;
use \Framework\Email\SendMail;
use Framework\Session\FlashService;


class ContactAction{

  /**
  * @var RendererInterface
  */
  private $renderer;

  /**
   * @var FirstPageTable
   */
  private $firstPageTable;

  /**
   * @var ImageTable
   */
  private $imageTable;
  /**
   * @var FlashService
   */
  private $flash;



  public function __construct(RendererInterface $renderer, Router $router,FlashService $flash )
  {
    $this->renderer = $renderer;
    $this->flash = $flash;
  }
   public function __invoke(Request $request)
  {

        if ($request->getMethod() === 'POST') {

          $params =$this->getParams($request);
          $validator = $this->getValidators($params);
          if($validator->isValid()){
            $item['message']=$params['message'];
          
            (new SendMail($this->renderer))
              ->receiver('emeric.lebbrecht@gmail.com')
              ->header($params['name'],$params['email'])
              ->subject($params['subjet'])
              ->message('@blog/contact/mail/contactMail',$item)
              ->send();
            $this->flash->success('le message a bien été envoyer');
            return $this->renderer->render('@blog/contact/contact');
          }

          $errors = $validator->getErrors();

        }


    return $this->renderer->render('@blog/contact/contact',compact('errors'));

  }

  private function getParams (Request $request){
    $params = array_merge($request->getParsedBody());
    if(!empty($params['message'])){
      $params['message'] = strip_tags($params['message']);
    }
    return array_filter($params, function ($key) {
      return in_array($key, ['name','email','subjet','message']);
    }, ARRAY_FILTER_USE_KEY);
  }


  private function getValidators(array $params){

    return (new Validator($params))
      ->required('name','email','subjet','message')
      ->length('name',2,255)
      ->length('subjet',2,255)
      ->mail('email')
      ->length('message',10);

  }



}
