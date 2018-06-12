<?php
namespace App\Model\Entity;


class Commentary {

  public $id;

  public $text;

  public $createdate;

  public $postId;

  public $commentId;

  public $userId;


  public function setCreatedate($date){
    $this->date = $date;
    $this->createdate = new \DateTime($date);
  }

}
