<?php
namespace Framework\Twig;

/**
 * serie d'extention concernant les textes
 */
class FormExtension extends \Twig_Extension {

/**
 * @return \Twig_SimpleFunction[]
 */
  public function getFunctions(): array
  {
    return[
    new \Twig_SimpleFunction('field',[$this,'field'],[
      'is_safe'=>['html'],
      'needs_context'=>true
    ])
    ];
  }


  public function field($context, string $key, $value, ?string $label = null, array $option = [])
  {
    $type = $option['type'] ?? 'text';
    $error = $this->getErrorHtml($context, $key);
    $class = 'form-group';

    $attributes = [
      'class' => trim('form-control ' . ($option['class'] ?? '')),
      'name' => $key,
      'id' => $key
    ];

    if ($error){

      $attributes['class'] .= ' is-invalid';

    }
    if($type === 'textarea') {
      $input = $this->textarea( $value, $attributes);
    }elseif($type === 'file'){

      $input = $this->file( $attributes);

    }elseif (array_key_exists('options', $option)){

        $input = $this->select( $value, $option['options'], $attributes);

    } else {
      $input = $this->input( $value, $attributes);
    }

    return "<div class=\"". $class ."\">
      <label for=\"title\">{$label}</label>
      {$input}
      {$error}
    </div>";
  }


  private Function getErrorHtml($context, $key){
    $error = $context['errors'][$key] ?? false;
    if ($error){
          return "<small class=\"invalid-feedback\">{$error}</small>";
        }
    return "";

  }

  private Function input( ?string $value, array $attributes): string{

    return "<input type=\"text\" ". $this->getHtmlFromArray($attributes) ." value=\"{$value}\">";

  }

  private Function file(array $attributes): string{

    return "<input type=\"file\" ". $this->getHtmlFromArray($attributes) .">";

  }

  private Function textarea( ?string $value, array $attributes): string{

    return "<textarea ". $this->getHtmlFromArray($attributes) .">{$value}</textarea>";

  }






  private Function select(?string $value, array $options ,array $attributes){

    $htmlOptions= array_reduce(array_keys($options), function(string $html, string $key)use ($options,$value) {
      $params = ['value' => $key, 'selected' => $key === $value];
      return $html .'<option '. $this->getHtmlFromArray($params) . '>'. $options[$key] .'</option>';
    }, "");

      return "<select ". $this->getHtmlFromArray($attributes) .">$htmlOptions</select>";
  }

  private Function getHtmlFromArray(array $attributes){
    $htmlParts = [];

    foreach($attributes as $key => $value){
      if($value === true){
        $htmlParts[] = (string) $key;
      }elseif ($value !== false) {
        $htmlParts[] = "$key=\"$value\"";
      }

    }

    return implode('', $htmlParts);
  }




}
