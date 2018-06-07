<?php

namespace Framework\Email;

use Psr\Container\ContainerInterface;
use Framework\Renderer\RendererInterface;

class SendMail{


/**
 * @var string
 */
  private $Receiver;

  /**
   * @var array
   */
  private $Subject;

  /**
   * @var string
   */
  private $Message;

  /**
   * @var string
   */
  private $Header;

  /**
   * @var string
   */
  private $Template;

  /**
   * @var RendererInterface
   */
  private $renderer;
  /**
   * @var \Twig_Environment
   */
  private $twig;




  public function __construct(RendererInterface $renderer){

    $this->renderer = $renderer;
  }

  public function send(){

    mail($this->Receiver,$this->Subject,$this->Message,$this->Header);
  }
public function receiver(string $Receiver): self{

    $this->Receiver = $Receiver;
    return $this;

}

public function subject(string $Subject): self{

  $this->Subject = $Subject;
  return $this;


}

public function message(string $pathMail,array $messageParams=[]): self{

  $this->Message = $this->renderer->render($pathMail, $messageParams);
  return $this;


}

public function header( string $name, string $email): self{

  $Header = "MIME-Version: 1.0\r\n";
  $Header .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
  $Header .= "From: ".$name." <".$email.">\r";
  $this->Header = $Header;
  return $this;


}



}
