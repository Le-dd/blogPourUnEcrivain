<?php
namespace Framework\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class CallableMiddleware implements MiddlewareInterface {

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
