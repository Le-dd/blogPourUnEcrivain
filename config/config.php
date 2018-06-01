<?php
use \Framework\Renderer\RendererInterface;
use \Framework\Renderer\TwigRendererFactory;
use \Framework\Router\RouterTwigExtension;
use \Psr\Container\ContainerInterface;
use \Framework\Twig\PagerFantaExtension;
use \Framework\Twig\TextExtension;
use \Framework\Twig\TimeExtension;
use \Framework\Twig\FlashExtension;
use \Framework\Twig\FormExtension;
use \Framework\Twig\CsrfExtension;
use \Framework\Session\PHPSession;
use \Framework\Session\SessionInterface;
use \Framework\Middleware\CsrfMiddleware;
return[
  'database.host'=>'localhost',
  'database.username'=>'root',
  'database.password'=>'root',
  'database.name'=>'billet_alaska',
  'views.path' => dirname(__DIR__).'/src/Layout',
  'twig.extensions'=>[
    \DI\get(RouterTwigExtension::class),
    \DI\get(PagerFantaExtension::class),
    \DI\get(TextExtension::class),
    \DI\get(TimeExtension::class),
    \DI\get(FlashExtension::class),
    \DI\get(FormExtension::class),
    \DI\get(CsrfExtension::class)

  ],
  SessionInterface::class => DI\autowire(PHPSession::class),
  CsrfMiddleware::class=>\DI\autowire()->constructor(\DI\get(SessionInterface::class)),
  \Framework\Router::class => DI\autowire(),
  RendererInterface::class => DI\factory(TwigRendererFactory::class),
  \PDO::class => function(ContainerInterface $c){
    return new PDO( 'mysql:host='. $c->get('database.host').';dbname=' . $c->get('database.name'),
    $c->get('database.username'),
    $c->get('database.password'),
    [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
  );
  }
];
