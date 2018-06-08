<?php

namespace App\Auth\ModelAuth;
use Framework\Auth;
use Framework\Auth\User;
use Framework\Session\SessionInterface;
use App\Model\UserTable;
use App\Model\PermissionTable;


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

  public function __construct(UserTable $userTable, SessionInterface $session,PermissionTable $permissionTable)
  {
    $this->session = $session;
    $this->userTable = $userTable;
    $this->permissionTable = $permissionTable;
  }


  public function login(string $username, string $password): ?User
  {
    if(empty($username) || empty($password)){
      return null;
    }

    $user = $this->userTable->findBy('login', $username);


    if ($user && password_verify($password, $user->password)) {


      $this->session->set('auth.user', $user->id);
      $permission = $this->permissionTable->findBY('id',$user->getRoles());
      $this->session->set('auth.permit', $permission->getPermit());

      return $user;

    }

    return null;
  }


  public function logout(): void
  {
      $this->session->delete('auth.user');
      $this->session->delete('auth.permit');

  }


  public function getUser(): ?User{

    if($this->user){
      return $this->user;
    }
    $userId = $this->session->get('auth.user');
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



  public function getTable(){


      return $this->userTable;

  }
}
