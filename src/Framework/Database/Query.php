<?php
namespace Framework\Database;

use Pagerfanta\Pagerfanta;

class Query implements \IteratorAggregate {

    private $select;

    private $from ;

    private $where = [];

    private $entity;

    private $group;

    private $order = [];

    private $joins = [];

    private $limit;

    private $params = [];

    /**
     * @var \PDO
     */
    private $pdo;






    public function __construct(?\PDO $pdo = null)
    {
      $this->pdo = $pdo;
    }

  public function from(string $table, ?string $alias = null ): self{
    if($alias) {
      $this->from[$table] = $alias;
    }else {
      $this->from[] = $table;
    }

    return $this;
  }

  public function select(string ...$fields): self {
      $this->select = $fields;
      return $this;
  }

  public function limit(int $length, int $offset = 0): self {
      $this->limit = "$offset, $length";
      return $this;
  }

  public function order(string $orders ): self
  {
    $this->order[] = $orders;
    return $this;
  }

  public function join(string $table, string $condition, string $type = "left" ): self
  {
    $this->joins[$type][] = [$table, $condition];
    return $this;
  }


  public function where(string ...$condition): self {

      $this->where = array_merge($this->where,$condition);
      return $this;
  }

  public function count():int {

    $query = clone $this;
    $table = current($this->from);
    $query->select("COUNT($table.id)");
    return $query->execute()->fetchColumn();
  }



  public function params(array $params ): self
  {
    
    $this->params = array_merge($this->params,$params);
    return $this;
  }
  public function into($entity){

    $this->entity = $entity;
    return $this;

  }

  /**
   * Récupère un résultat
   * @return [type] [description]
   */
  public function fetch()
    {
      $record =$this->execute()->fetch(\PDO::FETCH_ASSOC);
      if ($record === false) {
        return false;
      }

      if ($this->entity) {
        return QueryHydrator::hydrate($record,$this->entity);
      }
      return $record;
    }


    public function fetchOrFail()
      {
        $record = $this->fetch();
        if($record === false) {
          throw new NoRecordException();
        }
        return $record;
      }

  public function fetchAll(): QueryResult
  {

    return new QueryResult(
       $this->execute()->fetchAll(\PDO::FETCH_ASSOC),
       $this->entity
     );


  }

public function paginate(int $perPage, int $currentPage = 1): Pagerfanta
{
  $paginator = new PaginatedQuery($this);

  return (new Pagerfanta($paginator))->setMaxPerPage($perPage)->setCurrentPage($currentPage);

}


  public function __toString() {

    $parts = ['SELECT'];

    if ($this->select){
      $parts[]=join(', ',$this->select);

    }else {
      $parts[] ='*';
    }
    $parts[] = 'FROM';
    $parts[] = $this->buildFrom();


    if (!empty($this->joins)){
        foreach($this->joins as $type => $join)
        {
          foreach($join as [$table, $condition])
          {
              $parts[] = strtoupper($type) . " JOIN $table ON $condition";

          }

        }
    }
    if (!empty($this->where))
    {
      $parts[] = 'WHERE';
      $parts[]="(" .join(') AND (', $this->where) . ")" ;
    }

    if (!empty($this->order))
    {
      $parts[] = 'ORDER BY';

      $parts[]= join(', ', $this->order) ;

    }

    if ($this->limit)
    {
      $parts[] = 'LIMIT '. $this->limit;

    }



    return join(' ',$parts);
  }




  private function buildFrom() :string
  {
    $from = [];
    foreach ($this->from as $key => $value) {
      if (is_string($key)) {
        $from[] = "$key as $value";
      }else{
        $from[] = "$value";
      }
    }
    return join(', ',$from);
  }


  private function execute()
  {
    $query = $this->__tostring();
    if (!empty($this->params)){
      $statement = $this->pdo->prepare($query);
      $statement->execute($this->params);
      return $statement;
    }


      return $this->pdo->query($query);

  }


public function getIterator(){
  return $this->fetchAll();
}



}
