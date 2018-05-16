<?php
namespace App\Blog\Table;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;

class PostTable {
/**
 * @var \PDO
 */
  private $pdo;

  public function __construct(\PDO $pdo)
  {

    $this->pdo = $pdo;

  }

/**
  * pagine les articles
  * @param  int $perPage
  * @return Pagerfanta
  */
  public function findPaginated(int $perPage, int $currentPage):Pagerfanta
  {
    $query = new PaginatedQuery(
        $this->pdo,
        'SELECT * FROM post',
        'SELECT COUNT(id) FROM post'
    );
    return (new Pagerfanta($query))
        ->setMaxPerPage($perPage)
        ->setCurrentPage($currentPage);
    }

/**
 * Recupère un article à partir de son ID
 * @param  int $id
 * @return \stdClass
 */

  public function find(int $id ): \stdClass
  {
    $query = $this->pdo
      ->prepare('SELECT * FROM post WHERE id = ?');
    $query->execute([$id]);
    return $query->fetch();
  }

}
