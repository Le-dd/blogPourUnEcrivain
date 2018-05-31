<?php

use\App\Admin\AdminModule;
use\App\Blog\BlogModule;
use\App\Auth\AuthModule;
use Framework\Auth\ForbiddenMiddleware;
use Framework\Auth\LoggedinMiddleware;
use\Middlewares\Whoops;
use Framework\Middleware\{
  TrailingSlashMiddleware,
  MethodMiddleware,
  RouterMiddleware,
  DispatcherMiddleware,
  NotFoundMiddleware,
  CsrfMiddleware
};

require dirname(__DIR__).'/vendor/autoload.php';

$app = (new \Framework\App( dirname(__DIR__).'/config/config.php' ))
    ->addModule(AdminModule::class)
    ->addModule(BlogModule::class)
    ->addModule(AuthModule::class);

$container = $app->getContainer();
$app->pipe(Whoops::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(ForbiddenMiddleware::class)
    ->pipe($container->get('admin.prefix'),LoggedinMiddleware::class)
    ->pipe(MethodMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class);

if(php_sapi_name() !=="cli"){
  $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
  \Http\Response\send($response);
}
