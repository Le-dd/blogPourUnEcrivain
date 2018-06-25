<?php
namespace App\Model;

use Framework\Database\Table;
use App\Model\Entity\User;


class UserTable extends Table
{
  protected $table = "user";

  protected $entity = User::class;

  public function findAllLastCon($params){

    return $this->makeQuery()
      ->select('u.*')
      ->where('last_auth >= :date')
      ->params($params)
      ->count();
  }


  public function findAllNewUser($params){

    return $this->makeQuery()
      ->select('u.*')
      ->where('create_date >= :date')
      ->params($params)
      ->count();
  }


}
