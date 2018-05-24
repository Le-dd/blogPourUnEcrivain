<?php
namespace App\Blog\Table;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Pagerfanta\Pagerfanta;
use App\Blog\Entity\Post;

class PostTable extends Table {

protected $entity = Post::class;

protected $table = 'post';

public function findPaginatedPublic(int $perPage, int $currentPage):Pagerfanta
{
  $query = new PaginatedQuery(
      $this->pdo,
      "SELECT p.*, c.name_locality, c.id
      FROM {$this->table} as p
      LEFT JOIN location as c ON c.id = p.location_id
      ORDER BY date DESC, time DESC",
      "SELECT COUNT(id) FROM {$this->table}",
      $this->entity
  );
  return (new Pagerfanta($query))
      ->setMaxPerPage($perPage)
      ->setCurrentPage($currentPage);
}

protected function paginationQuery() {
  return "SELECT p.id, p.title, c.name_locality
  FROM {$this->table} as p
  LEFT JOIN location as c ON p.location_id = c.id
  ORDER BY date DESC, time DESC";
}


}
