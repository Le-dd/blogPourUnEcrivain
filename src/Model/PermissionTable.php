<?php
namespace App\Model;

use Framework\Database\Table;
use App\Model\Entity\Permission;


class PermissionTable extends Table
{
  protected $table = "permission";

  protected $entity = Permission::class;


}
