<?php
namespace App\Upload;

use Framework\Upload;

class PostUpload extends Upload {

  protected $path = 'public/images';

  protected $format = [
    'thumb'=> [320, 180]
  ];


}
