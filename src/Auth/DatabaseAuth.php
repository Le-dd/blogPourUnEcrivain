<?php

namespace App\auth;
use Framework\Auth;
use Framework\Auth\User;
use Framework\Session\SessionInterface;

class DatabaseAuth implements Auth {

  /**
   * @var UserTable
   */
  private $userTable;

  private $session;

  /**
   * @var User
   */
  private $user;

  public function __construct(UserTable $userTable, SessionInterface $session)
  {
    $this->session = $session;
    $this->userTable = $userTable;
  }


  public function login(string $username, string $password): ?User
  {
    if(empty($username) || empty($password)){
      return null;
    }

    $user = $this->userTable->findBy('login', $username);


    if ($user && password_verify($password, $user->password)) {

      $this->session->set('auth.user', $user->id);
      return $user;

    }

    return null;
  }


  public function logout(): void
  {
      $this->session->delete('auth.user');

  }


  public function getUser(): ?User{

    if($this->user){
      return $this->user;
    }
    $userId = $this ->session->get('auth.user');
    if($userId){
      try{
        $this->user = $this->userTable->find($userId);
        return $this->user;
      } catch (NoRecordException $exception) {
        $this->session->delete('auth.user');
        return null;
      }
    }

    return null;
  }
}
