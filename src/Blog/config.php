<?php

use  \App\Blog\BlogModule;
use  App\Blog\TwigExtension\BlogTwigExtension;

return [
  'index.prefix'=>'/',
  'posts.prefix'=>'/posts',
  'category.prefix'=>'/location',
  'contact.prefix' => '/contact',
  'blog.widgets' => \DI\add([
    \DI\get( App\Blog\Widget\BlogWidget::class )
  ]),
  BlogTwigExtension::class => \DI\autowire()->constructorParameter('widgets',\DI\get('blog.widgets')),




];
