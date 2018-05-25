<?php
namespace Framework\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class TrailingSlashMiddleware{

  public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
  {
    $uri = $request->getUri()->getPath();
    if (!empty($uri) && $uri[-1] === "/")
    {
      if($uri === "/"){
        return (new Response())
        ->withStatus(301)
        ->withHeader('location','/Billet-simple-pour-l-Alaska');
      }else{
       return (new Response())
       ->withStatus(301)
       ->withHeader('location',substr($uri,0,-1));
     }

   }
   return $next($request);
  }
}
