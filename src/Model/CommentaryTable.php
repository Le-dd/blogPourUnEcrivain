<?php
namespace App\Model;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Framework\Database\Query;
use Pagerfanta\Pagerfanta;
use App\Model\Entity\Commentary;


class CommentaryTable extends Table {

protected $entity = Commentary::class;

protected $table = 'commentary';



public function findAll(){

  return $this->makeQuery()
    ->select('c.*');

}
public function findAllNull(array $params){

  return $this->findAll()
    ->where('c.post_id = :postId','c.comment_id IS NULL')
    ->order('createdate ASC')
    ->params($params);

}

public function findAllBy(array $params){

  return $this->findAll()
    ->where('c.post_id = :postId','c.comment_id = :commentId')
    ->order('createdate DESC')
    ->params($params);

}


}
