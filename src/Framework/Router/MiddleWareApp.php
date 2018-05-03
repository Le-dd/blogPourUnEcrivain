<?php
namespace Framework\Router;

use Psr\Http\server\MiddlewareInterface;
use Psr\Http\server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class MiddlewareApp implements MiddlewareInterface {

    public $callable;

    public function __construct ($callable) {

        $this->callable = $callable;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //Implement the process
    }

    public function getCallable() {

        return $this->callable;
    }

 }
