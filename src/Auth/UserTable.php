<?php
namespace App\auth;

use Framework\Database\Table;


class UserTable extends Table
{
  protected $table = "user";

  protected $entity = User::class;


}
