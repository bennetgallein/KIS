<?php
namespace API;

class UserObject {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUserByID($id) {
        $sth = $this->db->prepare('SELECT * FROM users WHERE id= :id LIMIT 1');
        $sth->bindParam(':id', $id);
        $sth->execute();
        if ($sth->rowCount() >= 1) {
            $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $results = $results[0];
            $array = array(
                '_id' => $results['_id'],
                'id' => $results['id'],
                'email' => $results['email'],
                'firstname' => $results['firstname'],
                'lastname' => $results['lastname'],
                'permissions' => $results['permissions'],
                'registered_at' => $results['registered_at']
            );
        } else {
            $array = array(
                'error' => 'true',
                'code' => '404',
                'message' => 'User with that ID not found!'
            );
        }
        return $array;
    }

    public function getUsers() {
        $sth = $this->db->prepare('SELECT * FROM users');
        $sth->execute();
        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $array = array();
        foreach ($results as $result) {
            $arr = array(
                '_id' => $result['_id'],
                'id' => $result['id'],
                'email' => $result['email'],
                'firstname' => $result['firstname'],
                'lastname' => $result['lastname'],
                'permissions' => $result['permissions'],
                'registered_at' => $result['registered_at']
            );
            array_push($array, $arr);
        }
        return $array;
    }

    public function getAddress($id) {
        $sth = $this->db->prepare('SELECT * FROM adresses WHERE userid = :id LIMIT 1');
        $sth->bindParam(':id', $id);
        $res = $sth->execute();
        if ($sth->rowCount() >= 1) {
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $arr = array(
                'address' => $result['adress'],
                'company' => $result['company'],
                'city' => $result['city'],
                'country' => $result['country'],
                'postalcode' => $result['postalcode']
            );
        } else {
            $arr = array(
                'error' => true,
                'code' => '404',
                'message' => 'Error 404. User has no data set!'
            );
        }
        return $arr;
    }
}