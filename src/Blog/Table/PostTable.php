<?php
namespace App\Blog\Table;

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
 * @return \stdClass[]
 */
  public function findPaginated():array
  {

    return $this->pdo
      ->query('SELECT * FROM post ORDER BY date DESC LIMIT 10')
      ->fetchAll();

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
