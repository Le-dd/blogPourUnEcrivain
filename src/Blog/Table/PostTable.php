<?php
namespace App\Blog\Table;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;
use App\Blog\Entity\Post;

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
        'SELECT * FROM post ORDER BY date DESC, time DESC',
        'SELECT COUNT(id) FROM post',
        Post::class
    );
    return (new Pagerfanta($query))
        ->setMaxPerPage($perPage)
        ->setCurrentPage($currentPage);
    }

/**
 * Recupère un article à partir de son ID
 * @param  int $id
 * @return Post
 */

  public function find(int $id ): Post
  {
    $query = $this->pdo
      ->prepare('SELECT * FROM post WHERE id = ?');
    $query->execute([$id]);
    $query->setFetchMode(\PDO::FETCH_CLASS, Post::class);
    return $query->fetch();
  }

  /**
   * Met à jours un enregistrement au niveau de la base de donnée
   * @param  int   id
   * @param  array $fields
   * @return bool
   */
  public function update(int $id, array $params): bool
  {
    $fieldQuery = join(', ', array_map(function($field){
      return "$field = :$field";
    }, array_keys($params)));
    $params["id"] = $id;
    $statement = $this->pdo->prepare("UPDATE post SET $fieldQuery Where id = :id");
    return $statement->execute($params);

  }

}
