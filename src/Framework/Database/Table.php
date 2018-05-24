<?php
namespace Framework\Database;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;
use App\Blog\Entity\Post;

class Table {
  /**
   * @var \PDO
   */
  protected $pdo;

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
 * Recupère une liste clef valeur de nos enregistrement
 */

public function findList():array
{
  $results = $this->pdo
    ->query("SELECT id, name_locality FROM {$this->table} ")
    ->fetchAll(\PDO::FETCH_NUM);
$list = [];
foreach ($results as $result){

  $list[$result[0]]= $result[1];
}

  return $list;
}

/**
 * Recupère une liste clef valeur de nos enregistrement
 */

public function findAll():array
{
  $query = $this->pdo->query("SELECT * FROM {$this->table} ");
  if($this->entity){
    $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
  }else {
    $query->setFetchMode(\PDO::FETCH_OBJ);
  }

  return $query->fetchAll();
}

/**
 * Recupère une ligne par rapport à un champs
 */

public function findBY(string $field, string $value)
{
  return $this->fetchOrFail("SELECT * FROM {$this->table} Where $field = ?",[$value]);

}

/**
 * Recupère un élement à partir de son ID
 * @param  int $id
 * @return mixed
 */

  public function find(int $id )
  {
    return $this->fetchOrFail("SELECT * FROM {$this->table} WHERE id = ?", [$id]);


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
    $query = $this->pdo->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id = :id ");
    return $query->execute($params);

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
    $query = $this->pdo->prepare("INSERT INTO {$this->table} ($fields)  VALUES ($values)");
    return $query->execute($params);
  }

  /**
   * Supprime un enregistrement au niveau de la base de donnée
   * @param  int   id
   * @return bool
   */
  public function delete(int $id): bool
  {
    $query = $this->pdo->prepare("DELETE FROM {$this->table}  WHERE id = ? ");
    return $query->execute([$id]);

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



    /**
     * renvoie l'intance de PDO
     * @return \PDO
     */
      public function getPdo(): \PDO
      {
        return $this->pdo;
      }

    /**
     * Permet d'executer une requete et de récupérer le premier résultat
     * @param  string $query
     * @param  array  $params
     * @return mixed
     * @throws NoRecordException
     */
    protected function fetchOrFail(string $query, array $params = [])
      {
          $query = $this->pdo->prepare($query);
          $query->execute($params);
          if($this->entity){
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
          }else {
            $query->setFetchMode(\PDO::FETCH_OBJ);
          }
          $record = $query->fetch();
          if ($record == false) {
            throw new NoRecordException();
          }
          return $record ;
      }
}
