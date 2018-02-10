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
    private $changelog;

    public function __construct() {
        $json_file = dirname(__FILE__) . "/../config.json";
        $json = file_get_contents($json_file);
        $this->cfg = json_decode($json, true);

        $lang_file = dirname(__FILE__) . "/../languages/messages_" . $this->cfg['lang'] . ".json";
        $json = file_get_contents($lang_file);
        $this->lang = json_decode($json, true);

        include(dirname(__FILE__) . '/../modules/modules_loader.php');
        $module_loader = new ModuleLoader();
        $this->modules = ($module_loader->getModules());
        usort($this->modules, array("Module", "cmp"));


        $changelog_file = dirname(__FILE__) . "/../changelog.json";
        $changelog_json = file_get_contents($changelog_file);
        $this->changelog = json_decode($changelog_json, true);
        $this->connect();

    }

    public function __destruct() {
        //$this->disconnect();
    }

    public function connect() {
        $this->connection = new MySQLi($this->cfg["database"][0]["host"], $this->cfg['database'][0]['user'], $this->cfg['database'][0]['password'], $this->cfg['database'][0]['database']);
        $this->connection->set_charset("utf8");
        //$this->simpleQuery("SET names UTF-8");
    }

    public function disconnect() {
        $this->connection->close();
    }

    public function prepareQuery($query, $params) {
        $prep = $this->connection->prepare($query);
        $types = str_repeat('s', count($params));
        $prep->bind_param($types, ...$params);
        $res = $prep->execute();
        return $res;
    }

    public function simpleQuery($query) {
        $res = $this->connection->query($query);
        echo $this->connection->error;
        return $res;
    }

    public function escape($toEscape) {
        $value = $this->connection->real_escape_string($toEscape);
        return $value;
    }

    public function getConfig() {
        return $this->cfg;
    }

    public function getChangelog() {
        return $this->changelog['changelogs'];
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

    public function getModuleByName($name) {
        foreach ($this->getModules() as $module) {
            if ($name == $module->getName()) {
                return $module;
            }
        }
        return false;
    }

    public function getFooter() {
        return $this->cfg['footer'];
    }

    public function getConnection() {
        return $this->connection;
    }

    public function random_string() {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(16);
            $str = bin2hex($bytes);
        } else
            if (function_exists('openssl_random_pseudo_bytes')) {
                $bytes = openssl_random_pseudo_bytes(16);
                $str = bin2hex($bytes);
            } else if (function_exists('mcrypt_create_iv')) {
                $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
                $str = bin2hex($bytes);
            } else {
                $str = md5(uniqid('6A0vqpSfAkDq1Srbt82u', true));
            }
        return $str;
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function triggerFatalError() {
        trigger_error("Fatal error", E_USER_ERROR);
    }

    function redirect($url) {
        if (!headers_sent()) {
            header('Location: ' . $url);
            exit;
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
            echo '</noscript>';
            exit;
        }
    }
}

function error_found() {
    @header("Location: error.php");
}

//set_error_handler('error_found');
session_start();