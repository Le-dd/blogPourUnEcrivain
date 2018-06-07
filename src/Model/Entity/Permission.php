<?php
namespace App\Model\Entity;


class Permission {

  public $id;

  public $name;

  public $permit;


  public function getName(): string{
    return $this->name;
  }

  public function getPermit(){

    return $this->permit;
  }
}
