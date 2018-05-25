<?php

use  \App\Blog\BlogModule;
use  function DI\{autowire,get};
return [
  'index.prefix'=>'/index',
  'posts.prefix'=>'/posts',
  'category.prefix'=>'/location',
  'admin.widgets' => \DI\add([
    get( \App\Blog\BlogWidget::class )
  ])

];
