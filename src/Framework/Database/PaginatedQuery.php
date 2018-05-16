<?php
namespace Framework\Database;

use Pagerfanta\Adapter\AdapterInterface;

class PaginatedQuery implements AdapterInterface{

/**
 * @var \PDO
 */
private $pdo;

  /**
   * @var string
   */
private $query;

  /**
   * @var string
   */
private $countQuery;



/**
 * @param PDO    $pdo
 * @param string $query requête permettant de recupéré x resultat
 * @param string $countQuery requête permettant de compter la nombre de résultat total
 */
public function __construct( \PDO $pdo,string $query,string $countQuery)
{
  $this->pdo = $pdo;
  $this->query = $query;
  $this->countQuery = $countQuery;

}

  /**
 * Returns the number of results.
 *
 * @return integer The number of results.
 */
function getNbResults(): int
{

  return $this->pdo->query($this->countQuery)->fetchColumn();

}

/**
 * Returns an slice of the results.
 *
 * @param integer $offset The offset.
 * @param integer $length The length.
 *
 * @return array|\Iterator|\IteratorAggregate The slice.
 */
function getSlice($offset,$length)
{
  $statement = $this->pdo->prepare($this->query .' LIMIT :offset, :length');
  $statement->bindParam('offset', $offset, \PDO::PARAM_INT);
  $statement->bindParam('length', $length, \PDO::PARAM_INT);
  $statement->execute();
  return $statement->fetchAll();
}

}
