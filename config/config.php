<?php
use \Framework\Renderer\RendererInterface;
use \Framework\Renderer\TwigRendererFactory;
return[

  'views.path' => dirname(__DIR__).'/views',
  \Framework\Router::class => DI\autowire(),
  RendererInterface::class => \DI\factory(TwigRendererFactory::class)


];
