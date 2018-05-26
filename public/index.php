<?php

use\App\Admin\AdminModule;
use\App\Blog\BlogModule;
use\Middlewares\Whoops;
use Framework\Middleware\{
  TrailingSlashMiddleware,
  MethodMiddleware,
  RouterMiddleware,
  DispatcherMiddleware,
  NotFoundMiddleware
};

require dirname(__DIR__).'/vendor/autoload.php';

$app = (new \Framework\App( dirname(__DIR__).'/config/config.php' ))

      ->addModule(AdminModule::class)
      ->addModule(BlogModule::class)
      ->pipe(Whoops::class)
      ->pipe(TrailingSlashMiddleware::class)
      ->pipe(MethodMiddleware::class)
      ->pipe(RouterMiddleware::class)
      ->pipe(DispatcherMiddleware::class)
      ->pipe(NotFoundMiddleware::class);

if(php_sapi_name() !=="cli"){
  $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
  \Http\Response\send($response);
}
