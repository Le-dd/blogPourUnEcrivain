<?php
namespace App\Blog\Entity;


class Post
{

  public $id;

  public  $slug;

  public  $title;

  public  $main;

  public  $date;

  public  $time;

  public  $latitude;

  public  $longitude;

  public  $visible;

  public  $locationId;

  public  $namePlace;

  public $dateTime;

  public function __construct()
    {
      if ($this->date) {
        if ($this->time) {
          $datePlusTime = $this->date ." ". $this->time;
          $this->dateTime = new \DateTime($datePlusTime);
        }else{
          $this->dateTime = new \DateTime($this->date);
        }
      }
    }

    public function setDateTime(){

      if (is_null($this->datetime) && $this->date && $this->time ) {

          $datePlusTime = $this->date ." ". $this->time;
          $this->dateTime = new \DateTime($datePlusTime);

    }

  }


}
