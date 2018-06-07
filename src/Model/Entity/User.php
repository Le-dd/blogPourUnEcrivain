<?php
namespace App\Model\Entity;
use Framework\Auth\User as userInt;

class User implements userInt{

  public $id;

  public $login;

  public $password;

  public $email;

  public $permissionId;

  public function getUsername(): string{
    return $this->login;
  }

  public function getRoles(){

    return $this->permissionId;
  }
}
