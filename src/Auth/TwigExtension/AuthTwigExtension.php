<?php
namespace App\Auth\TwigExtension;

use \Framework\Auth;
use Framework\Session\SessionInterface;

class AuthTwigExtension extends \Twig_Extension {

  /**
   * @var Auth
   */
  private $auth;

  /**
   * @var SessionInterface
   */
  private $session;

  public function __construct( Auth $auth, SessionInterface $session )
  {
    $this->auth = $auth;
    $this->session = $session;
  }


/**
 * @return \Twig_SimpleFunction[]
 */
  public function getFunctions(): array
  {
    return[
    new \Twig_SimpleFunction('current_user',[$this->auth,'getUser']),
    new \Twig_SimpleFunction('current_permission',[$this,'getPermission'])
    ];
  }

  public function getPermission(){
    $permission = $this->session->get('auth.permit');
    return $permission ;
  }

}
