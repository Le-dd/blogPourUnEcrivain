<?php
namespace App\Blog\Table;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Pagerfanta\Pagerfanta;
use App\Blog\Entity\Post;

class PostTable extends Table {

protected $entity = Post::class;

protected $table = 'post';

protected function paginationQuery() {
  return parent::paginationQuery() . " ORDER BY date DESC, time DESC";
}


}
