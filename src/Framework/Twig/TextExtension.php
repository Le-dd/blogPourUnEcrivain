<?php
namespace Framework\Twig;

/**
 * serie d'extention concernant les textes
 */
class TextExtension extends \Twig_Extension {

/**
 * @return \Twig_SimpleFilter[]
 */
  public function getFilters(): array
  {
    return[
    new \Twig_SimpleFilter('excerpt',[$this,'excerpt'])
    ];
  }

/**
 * Renvoie un extrait du contenu
 * @param  string  $content
 * @param  integer $maxLength
 * @return string
 */
  public function excerpt(string $content,int $maxLength = 100): string
  {
    if(is_null($content)) {
      return '';
    }
    if (mb_strlen($content) > $maxLength ){
      $excerpt = mb_substr($content, 0, $maxLength);
      $lastSpace = mb_strrpos($excerpt, ' ');
      return $excerpt = mb_substr($content, 0, $lastSpace) . '...';
    }
    return $content;
  }
}
