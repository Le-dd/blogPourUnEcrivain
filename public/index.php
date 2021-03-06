<?php

use\App\Admin\AdminModule;
use\App\Blog\BlogModule;
use\App\Auth\AuthModule;
use\App\Ajax\AjaxModule;
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
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$app = (new \Framework\App('config/config.php' ))
    ->addModule(BlogModule::class)
    ->addModule(AdminModule::class)
    ->addModule(AuthModule::class)
    ->addModule(AjaxModule::class);

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
