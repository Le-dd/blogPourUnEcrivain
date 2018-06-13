<?php

namespace Framework;

use Framework\Validator\ValidationError;
use Framework\Database\Table;

class Validator {

private const MIME_TYPES = [
  'jpg' => 'image/jpeg',
  'png' => 'image/png',
  'pdf' => 'application/pdf'
];
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


/**
 * Vérifie que la clef existe dans la base de donnée
 * @param  string $key
 * @param  string $table
 * @param  PDO    $pdo
 * @return Validator
 */

public function exists(string $key,string $champ, string $table, \PDO $pdo)
{
  $value = $this->getValue($key);
  $statement = $pdo->prepare("SELECT $champ FROM $table  WHERE $champ = ? ");
  $statement->execute([$value]);

    if ($statement->fetchColumn() === false) {
      $this->addError($key,'exists',[$table]);

    };
    return $this;
}

/**
 * Vérifie si les deux string sont égale
 * @param  string $key
 * @param  string $keyCheck
 * @return Validator
 */

public function isEqual(string $key, ?string $keyCheck)
{
  $value = $this->getValue($key);
  if($value !== $keyCheck){
    $this->addError($key,'notEqual');
  }
  return $this;
}

/**
 * Vérifie si le hash est valide
 * @param  string $key
 * @param  string $keyCheck
 * @return Validator
 */

public function ishash(string $key, ?string $hashCheck)
{
  $value = $this->getValue($key);

  if(!password_verify($value, $hashCheck)){
    $this->addError($key,'notHash');
  }
  return $this;
}

/**
 * Vérifie si le mail est valide
 * @param  string $key
 * @param  string $keyCheck
 * @return Validator
 */

public function mail(string $key)
{
  $value = $this->getValue($key);
  if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
    $this->addError($key,'notMail');
  }
  return $this;
}




/**
 * Vérifie que la clef est unique dans la base de donnée
 * @param  string $key
 * @param  string $table
 * @param  PDO    $pdo
 * @param  int $exclude
 * @return Validator
 */

public function unique(string $key,string $champ='*', string $table, \PDO $pdo,int $exclude = null): self
{
  $value = $this->getValue($key);
  $query= "SELECT $champ FROM $table  WHERE $key = ? ";
  $params = [$value];

  if($exclude !== null){
    $query .="AND $key != ?";
    $params[] = $exclude;
  }

  $statement = $pdo->prepare($query);
  $statement->execute($params);

    if ($statement->fetchColumn() !== false) {
      $this->addError($key,'unique',[$value]);

    };
    return $this;
}

/**
 * Vérifie si le fichier a bien été uploadé
 * @param  string $key
 * @return self
 */

public function uploaded(string $key): self
{
  $file = $this->getValue($key);
  
  if($file === null || $file->getError() !== UPLOAD_ERR_OK){
    $this->addError($key, 'uploaded');
  }
  return $this;
}

/**
  * verifie le format de fichier
  * @param  string $key
  * @param  array  $extension
 */
public function extension(string $key,array $extensions){

  $file = $this->getValue($key);
  if($file !== null && $file->getError() === UPLOAD_ERR_OK){
    $type =$file->getClientMediaType();
    $extension = mb_strtolower(pathinfo($file->getClientFilename(), PATHINFO_EXTENSION));
    $expectedType = self::MIME_TYPES[$extension] ?? null;
    if(!in_array($extension, $extensions) || $expectedType !== $type) {
      $this->addError($key,'filetype', [join(',', $extensions)]);

    }
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
