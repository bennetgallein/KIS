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

    private $lang;

    public function __construct() {
        $json_file = dirname(__FILE__) . "/../config.json";
        $json = file_get_contents($json_file);
        $this->cfg = json_decode($json, true);

        $lang_file = dirname(__FILE__) . "/../messages_" . $this->cfg['lang'] . ".json";
        $json = file_get_contents($lang_file);
        $this->lang = json_decode($json, true);
    }

    private function connect() {
        $this->connection = $db = new MySQLi($this->cfg["database"][0]["host"], $this->cfg['database'][0]['user'], $this->cfg['database'][0]['password'], $this->cfg['database'][0]['database']);
    }

    private function disconnect() {
        $this->connection->close();
    }
    /*
     *
     * $params = array(
        "i" => $number,
        "s" => $string,
        "d" => $double
     * );
     *
     */
    public function prepareQuery($query, $params) {
        $this->connect();
        $prep = $this->connection->prepare($query);
        foreach ($params as $type => $item) {
            $prep->bind_param($type, $item);
        }
        return $prep->execute();
        $this->disconnect();
    }
    function getConfig() {
        return $this->cfg;
    }
    function gM($code) {
        return $this->lang[$code];
    }
}