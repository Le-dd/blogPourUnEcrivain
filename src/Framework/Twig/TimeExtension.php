<?php

namespace Framework\Twig;

/**
 * serie d'extention concernant les date et l'heure
 */
class TimeExtension extends \Twig_Extension {

/**
 * @return \Twig_SimpleFilter[]
 */
  public function getFilters(): array
  {
    return[
    new \Twig_SimpleFilter('ago', [$this,'ago'] , ['is_safe' => ['html']])
    ];
  }

  public function ago(\DateTime $date, string $format ="")
  {
    $texte = "le ".$date->format('d/m/Y'). " à ". $date->format('H:i:s');
    if(!empty($format)){
      $texte = $date->format($format);
    }
    return '<span class="timeago" datetime="' . $date->format(\dateTime::ISO8601).'">'.
    $texte.
    '</span>';
  }

}
