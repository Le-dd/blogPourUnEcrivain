<?php

use App\Auth\AuthModule;
use App\Auth\ModelAuth\DatabaseAuth;
use Framework\Auth;
use Framework\Auth\ForbiddenMiddleware;
use App\Auth\TwigExtension\AuthTwigExtension;

return [
  'auth.login' => '/login',
  'auth.logout' =>'/logout',
  'twig.extensions'=> \DI\add([
    \DI\get(AuthTwigExtension::class)

  ]),
  Auth::class => \DI\get(DatabaseAuth::class),
  ForbiddenMiddleware::class =>\DI\autowire()->constructorParameter('LoginPath',\DI\get('auth.login'))

];
