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
      'class' => 'form-control',
      'name' => $key,
      'id' => $key
    ];

    if ($error){

      $attributes['class'] .= ' is-invalid';

    }
    if($type === 'textarea') {
      $input = $this->textarea( $value, $attributes);
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

  private Function textarea( ?string $value, array $attributes): string{

    return "<textarea ". $this->getHtmlFromArray($attributes) .">{$value}</textarea>";

  }

  private Function getHtmlFromArray(array $attributes){

    return implode('', array_map(function($key, $value ){
      return "$key=\"$value\"";
    },array_keys($attributes),$attributes));
  }



}
