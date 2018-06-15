<?php
namespace App\Model;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Framework\Database\Query;
use Pagerfanta\Pagerfanta;
use App\Model\Entity\ImagePost;


class ImagePostTable extends Table {

protected $entity = ImagePost::class;

protected $table = 'image_post';

public function findAll(){

  return $this->makeQuery()
    ->select('i.*');

}
public function findAllBy($params){
return  $this->findAll()
->where('i.post_id = :postId')
->params($params);

}



}
