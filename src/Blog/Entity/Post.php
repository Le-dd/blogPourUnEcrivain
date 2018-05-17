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

  public  $location_id;

  public  $name_place;

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


}
