<?php
namespace Framework\Database;



class QueryResult implements \ArrayAccess, \Iterator {

  /**
   * @var array
   */
    private $records;

    private $entity;

    private $index = 0;

    private $hydratedRecords = [];



    public function __construct(array $records, ?string $entity = null)
    {
      $this->records = $records;
      $this->entity = $entity;
    }

    public function get(int $index)
    {
      if ($this->entity){
        if(!isset($this->hydratedRecords[$index])){
        $this->hydratedRecords[$index] = QueryHydrator::hydrate($this->records[$index],$this->entity);
      }
      return $this->hydratedRecords[$index];
      }

      return $this->entity;
    }

      public function current()
      {

        return $this->get($this->index);

      }

      public function next()
      {

        $this->index++;

      }

      public function key()
      {
        return $this->index;
      }

      public function valid()
      {
        return isset($this->records[$this->index]);
      }

      public function rewind()
      {
        $this->index = 0;
      }

      public function offsetExists($offset)
      {
        return isset($this->records[$offset]);
      }

      public function offsetGet($offset)
      {
        return $this->get($offset);
      }


      public function offsetSet($offset, $value)
      {
        throw new \Exception("Can't alter records");
      }
      public function offsetUnset($offset)
      {
        throw new \Exception("Can't alter records");
      }






}
