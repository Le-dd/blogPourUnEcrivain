<?php
namespace Framework\Database;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;
use App\Blog\Entity\Post;

class Table {
  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * Nom de la table en BDD
   * @var string
   */
  protected $table;

  /**
   * Entité a utiliser
   * @var string|null
   */
  protected $entity;

  public function __construct(\PDO $pdo)
  {

    $this->pdo = $pdo;

  }

/**
  * Pagine des élements
  *
  * @param  int $perPage
  * @return Pagerfanta
  */
  public function findPaginated(int $perPage, int $currentPage):Pagerfanta
  {
    $query = new PaginatedQuery(
        $this->pdo,
        $this->paginationQuery(),
        "SELECT COUNT(id) FROM {$this->table}",
        $this->entity
    );
    return (new Pagerfanta($query))
        ->setMaxPerPage($perPage)
        ->setCurrentPage($currentPage);
    }

protected function paginationQuery() {
  return "SELECT * FROM {$this->table}";
}

/**
 * Recupère un élement à partir de son ID
 * @param  int $id
 * @return mixed
 */

  public function find(int $id )
  {
    $query = $this->pdo
      ->prepare("SELECT * FROM {$this->table} WHERE id = ?");
    $query->execute([$id]);
    if($this->entity){
      $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
    }
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
    $fieldQuery = $this->buildFieldQuery($params);
    $params["id"] = $id;
    $statement = $this->pdo->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id = :id ");
    return $statement->execute($params);

  }

  /**
   * Ajoute un enregistrement au niveau de la base de donnée
   * @param  array $fields
   * @return bool
   */
  public function insert(array $params){

    $fields = array_keys($params);
    $values = join(',',array_map(function ($field){
      return ':'.$field;
    }, $fields));
    $fields = join(',',$fields);
    $statement = $this->pdo->prepare("INSERT INTO {$this->table} ($fields)  VALUES ($values)");
    return $statement->execute($params);
  }

  /**
   * Supprime un enregistrement au niveau de la base de donnée
   * @param  int   id
   * @return bool
   */
  public function delete(int $id): bool
  {
    $statement = $this->pdo->prepare("DELETE FROM {$this->table}  WHERE id = ? ");
    return $statement->execute([$id]);

  }


  private function buildFieldQuery(array $params){
    return join(', ', array_map(function($field){
      return "$field = :$field";
    }, array_keys($params)));
  }

/**
 * renvoie l'entity
 * @return mixed
 */
  public function getEntity(): string
  {
    return $this->entity;
  }

  /**
   * renvoie l'table
   * @return string
   */
    public function getTable(): string
    {
      return $this->table;
    }

}
