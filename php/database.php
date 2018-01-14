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
    private $modules;

    public function __construct() {
        $json_file = dirname(__FILE__) . "/../config.json";
        $json = file_get_contents($json_file);
        $this->cfg = json_decode($json, true);

        $lang_file = dirname(__FILE__) . "/../messages_" . $this->cfg['lang'] . ".json";
        $json = file_get_contents($lang_file);
        $this->lang = json_decode($json, true);

        include(dirname(__FILE__) . '/../modules/modules_loader.php');
        $module_loader = new ModuleLoader();
        $this->modules = $module_loader->getModules();
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
        $types = str_repeat('s', count($params));
        $prep->bind_param($types, ...$params);
        $res = $prep->execute();
        $this->disconnect();
        return $res;
    }

    public function simpleQuery($query) {
        $this->connect();
        $res = $this->connection->query($query);
        echo $this->connection->error;
        return $res;
    }

    public function escape($toEscape) {
        $this->connect();
        return $this->connection->real_escape_string($toEscape);
        $this->disconnect();
    }

    public function getConfig() {
        return $this->cfg;
    }

    public function m($code) {
        return $this->lang[$code];
    }

    public function e($code) {
        return $this->lang['errors'][$code];
    }

    public function getModules() {
        return $this->modules;
    }
}