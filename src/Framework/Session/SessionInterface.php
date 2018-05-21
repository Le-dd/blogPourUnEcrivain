<?php

namespace Framework\Session;


interface SessionInterface{

/**
 * Récupère une information en session
 * @param  string $key
 * @param  mixed $default
 * @return mixed
*/
public function get(string $key, $default = null) ;


/**
 * Ajoute une information en session
 * @param string $key
 * @param $value
 * @return mixed
 */
public function set(string $key, $value) ;
/**
 * Supprimer une clef
 * @param string $key
 */
public function delete(string $key) ;

}
