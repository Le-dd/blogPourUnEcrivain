<?php
namespace App\Admin\Upload;

use Framework\Upload;

class PostUpload extends Upload {

  protected $path = 'images/images';

  protected $format = [
    'thumb'=> [320, 180]
  ];


}
