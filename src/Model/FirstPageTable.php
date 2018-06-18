<?php
namespace App\Model;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Framework\Database\Query;
use Pagerfanta\Pagerfanta;
use App\Model\Entity\FirstPage;


class FirstPageTable extends Table {

protected $entity = FirstPage::class;

protected $table = 'first_page';



public function findAll(){

  return $this->makeQuery()
    ->select('f.*');

}



}
