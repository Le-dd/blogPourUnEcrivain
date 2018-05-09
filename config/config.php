<?php
use \Framework\Renderer\RendererInterface;
use \Framework\Renderer\TwigRendererFactory;
use \framework\Router\RouterTwigExtension;

return[

  'views.path' => dirname(__DIR__).'/views',
  'twig.extensions'=>[
    \DI\get(RouterTwigExtension::class)
  ],
  \Framework\Router::class => DI\autowire(),
  RendererInterface::class => DI\factory(TwigRendererFactory::class),

];
