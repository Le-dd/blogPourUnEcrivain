<?php
require '../vendor/autoload.php';

$builder = new\DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__).'/config/config.php');
$builder->addDefinitions(dirname(__DIR__).'/config.php');
$container = $builder->build();


$renderer = $container->get(\Framework\Renderer\RendererInterface::class);

$app = new \Framework\App($container,[
  \App\Blog\BlogModule::class
]);


$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
\Http\Response\send($response);
