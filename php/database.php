<?php
/**
 * Created by PhpStorm.
 * User: Bennet
 * Date: 1/13/2018
 * Time: 4:09 PM
 */

class DB {

    private $cfg;
    private $connection;

    public function __construct() {
        $json_file = dirname(__FILE__) . "/../config.json";
        $json = file_get_contents($json_file);
        $this->cfg = json_decode($json, true);
    }

    function connect() {
        $this->connection = $db = new MySQLi($this->cfg["database"][0]["host"], $this->cfg['database'][0]['user'], $this->cfg['database'][0]['password'], $this->cfg['database'][0]['database']);
    }

    function disconnect() {
        $this->connection->close();
    }
    function prepareQuery($query, $params) {
        $prep = $this->connection->prepare($query);
        foreach($samplearr as $key => $item){
    }
}