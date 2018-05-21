<?php
namespace Framework\Validator;

class ValidationError {

  private $key;

  private $rule;

  private $attributes;


  private $messages=[
    'required' => 'le champ %s est requis',
    'empty' => 'le champ %s ne peut être vide',
    'slug' => 'le champ %s n\'est pas un slug valide',
    'minLength'=>'le champ %s doit contenir plus de %d caractères',
    'maxLength'=>'le champ %s doit contenir moins de %d caractères',
    'betweenLength'=>'le champ %s doit contenir entre %d et %d caractères',
    'date'=>'le champ %s doit contenir une date au format YYYY-MM-DD',
    'time'=>'le champ %s doit contenir une date au format hh-mm-ss',
    'dateTime'=>'le contenu du champs %s doit contenir une erreur'
  ];

  public function __construct(string $key, string $rule, array $attributes = [])
  {
    $this->key=$key;
    $this->rule=$rule;
    $this->attributes=$attributes;
  }


  public function __toString()
  {
    $params = array_merge([$this->messages[$this->rule],$this->key],$this->attributes);

    return (string)call_user_func_array('sprintf', $params);

  }

}
