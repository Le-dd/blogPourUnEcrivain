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


    public function setDate($date){
      $this->date = $date;
      $this->dateTime = new \DateTime($date);
    }
    public function setTime($time){
      $this->time = $time;
      if($this->date){
        $datePlusTime = $this->date ." ". $this->time;
        $this->dateTime = new \DateTime($datePlusTime);
      }
      
    }

}
