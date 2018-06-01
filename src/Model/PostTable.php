<?php
namespace App\Model;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Framework\Database\Query;
use Pagerfanta\Pagerfanta;
use App\Model\Entity\Post;


class PostTable extends Table {

protected $entity = Post::class;

protected $table = 'post';



public function findAll(){
  $category = new CategoryTable($this->pdo);

  return $this->makeQuery()->join($category->getTable() . ' as l', 'l.id = p.location_id')
    ->select('p.* , l.name_locality , l.id as l_id')
    ->order('date DESC')
    ->order('time DESC');

}

public function findPublic(): Query{

  return $this->findAll()
    ->where('p.visible = 1');

}





}
