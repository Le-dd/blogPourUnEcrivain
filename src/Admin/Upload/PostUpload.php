<?php
namespace App\Admin\Upload;

use Framework\Upload;

class PostUpload extends Upload {

  protected $path = 'images';

  protected $formats = [
    'Vignette'=> [50, 50],
    'Miniature'=> [320, 180]
  ];


}
