<?php

namespace Framework;

use Framework\Validator\ValidationError;

class Validator {

/**
 * @var array
 */
private $params;

private $errors = [];

private $countTimeDate = 0;

public function __construct(array $params)
{
  $this->params = $params;
}


/**
 * Vérifie que les champs sont présent dans le tableau
 * @param  string[] ...$keys
 * @return Validator
 */
public function required( string ...$keys): self
{
  foreach ($keys as $key){
  $value = $this-> getValue($key);
  if(is_null($value)){
    $this->addError($key, 'required');

    }
  }
  return $this;

}

/**
 * Vérifie que lechamps n'est pas vide
 * @param  string $keys
 * @return self
 */

public function notEmpty(string ...$keys): self
{
  foreach ($keys as $key){
  $value = $this-> getValue($key);
  if(is_null($value) || empty($value)){
    $this->addError($key, 'empty');

    }
  }
  return $this;
}


public function length(string $key, ?int $min, ?int $max = null):self
{
  $value = $this-> getValue($key);
  $length = mb_strlen($value);
  if(
    !is_null($min) &&
    !is_null($max) &&
    ($length < $min || $length > $max)
    ) {
    $this->addError($key,'betweenLength', [$min, $max]);
    return $this;
    }

  if(
    !is_null($min) &&
    $length < $min
    ) {
    $this->addError($key,'minLength', [$min, $max]);
    return $this;
    }

  if(
    !is_null($max) &&
    $length > $max
    ) {
    $this->addError($key,'maxLength', [$min, $max]);
    return $this;
    }

    return $this;
}


/**
 * Vérifie que l'élément est un slug
 * @param  string $key
 * @return self
 */

public function slug(string $key):self
{
  $value = $this->getValue($key);
  $pattern = '/^[a-z0-9]+(-[a-z0-9]+)*$/';
  if(!is_null($value) && !preg_match($pattern, $value)){
    $this->addError($key,'slug');
  }
  return $this;
}
/**
 * Vérifie que l'élément est une date
 * @param  string $key
 * @return self
 */
public function date(string $key, string $format = "Y-m-d"):self
{
  $value = $this->getValue($key);
  $pattern = '/^[0-9]{4}(-[0-9]{2}){2}$/';
  if(!is_null($value) && !preg_match($pattern, $value)){
    $this->addError($key,'date');
    return $this;
  }

  $date = \DateTime::createFromFormat($format, $value);
  $errors = \DateTime::getLastErrors();
  if($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false){
    $this->addError($key,'dateTime');
  }
  return $this;
}
/**
 * Vérifie que l'élément est une heure
 * @param  string $key
 * @return self
 */
public function time(string $key, string $format = "H:i:s"):self
{
  $value = $this->getValue($key);
  $pattern = '/^[0-9]{2}(:[0-9]{2}){2}$/';
  if(!is_null($value) && !preg_match($pattern, $value)){
    $this->addError($key,'time');
    return $this;
  }
  $date = \DateTime::createFromFormat($format, $value);
  $errors = \DateTime::getLastErrors();
  if($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false){
    $this->addError($key,'dateTime');
  }
  return $this;
}



public function isValid():bool
{
  return empty($this->errors);
}


/**
 * Récupère les erreurs
 * @return ValidationError[]
 */
public function getErrors(): array {
  return $this->errors;
}

/**
 * Ajoute une erreur
 * @param string $key
 * @param string $rule
 * @param array $attributes
 */

private function addError(string $key, string $rule,array $attributes = []){
  $this->errors[$key] = new ValidationError($key, $rule, $attributes);
}

private function getValue(string $key)
{
  if(array_key_exists($key, $this->params)){
    return $this->params[$key];
  }
  return null;
}
}
