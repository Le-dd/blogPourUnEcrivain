<?php

use  \App\Blog\BlogModule;
use  function DI\{autowire,get};
return [
  'index.prefix'=>'/Billet-simple-pour-l-Alaska',
  'posts.prefix'=>'/posts',
  'category.prefix'=>'/location',
  'admin.widgets' => \DI\add([
    get( \App\Blog\BlogWidget::class )
  ])

];
