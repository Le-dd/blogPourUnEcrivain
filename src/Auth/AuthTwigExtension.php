<?php
namespace App\auth;

use \Framework\Auth;

class AuthTwigExtension extends \Twig_Extension {

  /**
   * @var Auth
   */
  private $auth;

  public function __construct( Auth $auth )
  {
    $this->auth = $auth;
  }


/**
 * @return \Twig_SimpleFunction[]
 */
  public function getFunctions(): array
  {
    return[
    new \Twig_SimpleFunction('current_user',[$this->auth,'getUser'])
    ];
  }



}
