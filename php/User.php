<?php
/**
 * Created by PhpStorm.
 * User: Bennet
 * Date: 1/13/2018
 * Time: 8:41 PM
 */

class User {

    private $realid;
    private $id;
    private $email;
    private $firstname;
    private $lastname;
    private $password;
    private $permissions;

    public function __construct($dataarr) {
        $this->realid = $dataarr['realid'];
        $this->id = $dataarr['id'];
        $this->email = $dataarr['email'];
        $this->firstname = $dataarr['firstname'];
        $this->lastname = $dataarr['lastname'];
        $this->password = $dataarr['password'];
        $this->$permissions = $dataarr['permissions'];
    }

    public function __sleep() {
        return array('realid', 'id', 'email', 'firstname', 'lastname');
    }

    public function getId() {
        return $this->id;
    }
    public function setEmail($x) {
        $this->email = $x;
    }
    public function getEmail() {
        return $this->email;
    }
    public function setFirstname($x) {
        $this->firstname = $x;
    }
    public function getFirstname() {
        return $this->firstname;
    }
    public function setLastname($x) {
        $this->lastname = $x;
    }
    public function getLastname() {
        return $this->lastname;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRealid() {
        return $this->realid;
    }
    public function getName() {
        return $this->firstname . " " . $this->lastname;
    }
    public function getPermissions() {
      return $this->permissions;
    }
}
