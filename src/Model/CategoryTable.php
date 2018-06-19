<?php
namespace App\Model;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Pagerfanta\Pagerfanta;
use App\Model\Entity\Location;


class CategoryTable extends Table {

protected $entity = Location::class;

protected $table = 'location';

public function findAllAjax(){
  {
    $results = $this->pdo
      ->query("SELECT * FROM {$this->table} ")
      ->fetchAll(\PDO::FETCH_NUM);
  $list = [];
  foreach ($results as $result){

    $list[$result[0]]= [
      'id' => $result[0],
      'latitude' => $result[1],
      'longitude' => $result[2],
      'nameLocality' => $result[3]
      ];
  }

    return $list;
  }

}


}
