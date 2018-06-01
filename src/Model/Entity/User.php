<?php
namespace App\Model\Entity;
use Framework\Auth\User as userInt;

class User implements userInt{

  public $id;

  public $login;

  public $password;

  public $email;

  public $permission_id;

  public function getUsername(): string{
    return $this->login;
  }

  public function getRoles():array{

    return $this->permission_id;
  }
}
