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

    public function __construct($dataarr) {
        $this->realid = $dataarr['realid'];
        $this->id = $dataarr['id'];
        $this->email = $dataarr['email'];
        $this->firstname = $dataarr['firstname'];
        $this->lastname = $dataarr['lastname'];
        $this->password = $dataarr['password'];
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getFirstname() {
        return $this->firstname;
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
}