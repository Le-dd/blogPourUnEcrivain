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
    'dateTime'=>'le contenu du champs %s doit contenir une erreur',
    'exists'=>'le contenu du champs %s n\'existe pas dans le table %s',
    'unique'=>'le contenu du champs %s existe déja dans le table [%s]',
    'filetype'=>'le champs %s n\'est pas au format valide(%s)',
    'uploaded'=>'Vous devez uploader un fichier',
    'notEqual'=>'les champs ne sont pas egaux',
    'notHash'=>'une erreur est survenu veuiller recommencer ',
    'notMail'=>'le champ n\'est pas un mail valide'


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
