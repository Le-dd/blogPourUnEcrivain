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
  return "SELECT p.id, p.title, p.slug, p.time, p.date, p.main, c.name_locality
  FROM {$this->table} as p
  LEFT JOIN location as c ON p.location_id = c.id
  ORDER BY date DESC, time DESC";
}


}
