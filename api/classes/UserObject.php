<?php

namespace API;

class UserObject {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUser($id) {
        $sth = $this->db->prepare('SELECT _id, id, email, firstname, lastname, permissions, registered_at, vertified FROM users WHERE id= :id LIMIT 1');
        $sth->bindParam(':id', $id);
        $sth->execute();
        $userclass = new \stdClass();
        if ($sth->rowCount() >= 1) {
            $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $results = $results[0];
            $address = $this->getAddress($results['id']);
            foreach ($results as $key => $data) {
                $userclass->$key = $data;
            }
            $userclass->address = $address;
        } else {
            $userclass->error = true;
            $userclass->code = 404;
            $userclass->message = 'User with that ID not found!';
        }
        return json_encode($userclass);
    }

    public function getUsers() {
        $sth = $this->db->prepare('SELECT _id, id, email, firstname, lastname, permissions, registered_at, vertified FROM users');
        $sth->execute();
        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $array = array();

        foreach ($results as $result) {
            $address = $this->getAddress($result['id']);
            $user = new \stdClass();
            foreach ($result as $key => $value) {
                $user->$key = $value;
            }
            $user->address = $address;
            array_push($array, $user);
        }
        return $array;
    }

    public function getAddress($id) {
        $sth = $this->db->prepare('SELECT * FROM adresses WHERE userid = :id LIMIT 1');
        $sth->bindParam(':id', $id);
        $sth->execute();
        $address = new \stdClass();
        if ($sth->rowCount() >= 1) {
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($result[0] as $key => $value) {
                $address->$key = utf8_encode($value);
            }
        } else {
            $address->error = true;
            $address->code = 404;
            $address->message = 'Error 404. User has no data set!';

        }
        return $address;
    }
}
