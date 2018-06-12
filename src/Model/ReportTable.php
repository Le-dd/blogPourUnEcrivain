<?php
namespace App\Model;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Framework\Database\Query;
use Pagerfanta\Pagerfanta;
use App\Model\Entity\Report;


class ReportTable extends Table {

protected $entity = Report::class;

protected $table = 'report';



public function findAll(){

  return $this->makeQuery()
    ->select('r.*');

}
public function findAllUserReport(array $params){

  return $this->findAll()
    ->where('r.user_id = :userId','r.comment_id = :commentId')
    ->params($params);

}

public function findAllReport(array $params){

  return $this->findAll()
    ->where('r.comment_id = :commentId')
    ->params($params);

}
public function findUserSign(array $params){

  return $this->makeQuery()
    ->select('u.email,u.login')
    ->where('r.comment_id = :commentId')
    ->join('user as u','u.id = r.user_id','right')
    ->params($params);

}


}
