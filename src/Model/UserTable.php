<?php
namespace App\Model;

use Framework\Database\Table;
use App\Model\Entity\User;


class UserTable extends Table
{
  protected $table = "user";

  protected $entity = User::class;


}
