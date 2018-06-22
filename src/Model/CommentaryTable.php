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



public function findAllPub(){

  return $this->makeQuery()
    ->select('c.*');

}



/**
 * Recupère une liste clef valeur de nos enregistrement
 */

public function findAll()
{
  return $this->makeQuery()
    ->select('c.*,r.comment_id as idComReport ,r2.count')
    ->join('report as r','c.id = r.comment_id','right')
    ->join2('(SELECT COUNT(id) as count , comment_id FROM report GROUP BY comment_id) as r2','r2.comment_id = r.comment_id','right')
    ->order('r2.count DESC');



}






public function findAllNull(array $params){

  return $this->findAllPub()
    ->where('c.post_id = :postId','c.comment_id IS NULL')
    ->order('createdate ASC')
    ->params($params);

}

public function findAllBy(array $params){

  return $this->findAllPub()
    ->where('c.post_id = :postId','c.comment_id = :commentId')
    ->order('createdate DESC')
    ->params($params);

}


}
