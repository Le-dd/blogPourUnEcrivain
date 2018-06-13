<?php
namespace App\Model;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Framework\Database\Query;
use Pagerfanta\Pagerfanta;
use App\Model\Entity\Image;


class ImageTable extends Table {

protected $entity = Image::class;

protected $table = 'image';



public function findAll(){

  return $this->makeQuery()
    ->select('i.*');

}




}
