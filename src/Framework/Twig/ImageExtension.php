<?php
namespace Framework\Twig;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Framework\Router;
use App\Model\ImagePostTable;
use App\Model\ImageTable;

class ImageExtension extends \Twig_Extension {

  /**
   * @var Router
   */
    private $router;

    /**
     * @var ImagePostTable
     */
    private $imagePostTable;

    /**
     * @var ImageTable
     */
    private $imageTable;

    public function __construct(Router $router,ImagePostTable $imagePostTable,ImageTable $imageTable)
    {
      $this->imagePostTable = $imagePostTable;
      $this->imageTable = $imageTable;
      $this->router = $router;
    }

    public function getFunctions()
    {
      return[
      new \Twig_SimpleFunction('imageType',[$this,'image'], ['is_safe' =>['html']]),
      new \Twig_SimpleFunction('url',[$this,'url'], ['is_safe' =>['html']]),
      new \Twig_SimpleFunction('alt',[$this,'alt'], ['is_safe' =>['html']])
      ];
    }


    public function image(string $url,?string $type =null):string
    {
      if(!is_null($type)){
        $extention = substr($url,-4);
        $url= str_replace($extention,'',$url);
            return "/images/{$url}_{$type}{$extention}";
      }

          return "/images/{$url}";
      }
      private function url($id){
        $params['postId']= $id;
        $existe = $this->imagePostTable->findAllBy($params)->count();
        if($existe !== 0){
          $idImage = $this->imagePostTable->findBY('post_id',$params['postId']);
          $image = $this->imageTable->find($idImage->imageId);
          return $image->url;
        }

        $result ='default.jpg';
        return $result;

      }


      private function alt($id){
        $params['postId']= $id;
        $existe = $this->imagePostTable->findAllBy($params)->count();
        if($existe !== 0){
          $idImage = $this->imagePostTable->findBY('post_id',$params['postId']);
          $image = $this->imageTable->find($idImage->imageId);
          return $image->alt;
        }

        $result ='image par default';
        return $result;

      }
}
