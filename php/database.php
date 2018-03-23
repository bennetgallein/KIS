<?php
/**
 * Created by PhpStorm.
 * User: Bennet
 * Date: 1/13/2018
 * Time: 4:09 PM
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../vendor/autoload.php";

class DB {

    private $cfg;
    private $connection;

    private $version = "boron";

    private $lang;
    private $modules;
    private $changelog;

    private $langs = array("Deutsch" => "de", "English" => "en");

    public function __construct() {

        $json_file = dirname(__FILE__) . "/../config.json";
        $json = file_get_contents($json_file);
        $this->cfg = json_decode($json, true);

        if (!isset($_GET['token'])) {
            $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $query = parse_url($url, PHP_URL_QUERY);
            $token = $this->random_string();
            if ($query) {
                $url .= '&token=' . $token;
            } else {
                $url .= '?token=' . $token;
            }
            $_SESSION['csrftoken'] = $token;
            header("Location: " . $url);
            exit();
        }
        
        if (isset($_COOKIE['lang']) && array_search($_COOKIE['lang'], $this->langs) != false) {
            $lang_file = dirname(__FILE__) . "/../languages/messages_" . $_COOKIE['lang'] . ".json"; 
        } else {
            $lang_file = dirname(__FILE__) . "/../languages/messages_" . $this->cfg['lang'] . ".json";
        }
        $json = file_get_contents($lang_file);
        $this->lang = json_decode($json, true);

        include(dirname(__FILE__) . '/../modules/modules_loader.php');
        $module_loader = new ModuleLoader($this->cfg);
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
    public function getVersion() {
        return $this->version;
    }
    public function connect() {
        $this->connection = new MySQLi($this->cfg["database"][0]["host"], $this->cfg['database'][0]['user'], $this->cfg['database'][0]['password'], $this->cfg['database'][0]['database']);
        $this->connection->set_charset("utf8");
        //$this->simpleQuery("SET NAMES 'utf8'");
    }

    public function disconnect() {
        $this->getConnection()->close();
    }

    public function check() {
        if (!mysqli_ping($this->connection)) {
            $this->disconnect();
            $this->connect();
        }
    }

    public function prepareQuery($query, $params) {
        $this->check();
        $prep = $this->connection->prepare($query);
        $types = str_repeat('s', count($params));
        $prep->bind_param($types, ...$params);
        $res = $prep->execute();
        return $res;
    }

    public function simpleQuery($query) {
        $this->check();
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
    public function getSupportedLangs() {
        return $this->langs;
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

    public function mail($to, $subject, $text) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'utf-8';
        try {
            //Server settings
            //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'wp12836029.mailout.server-he.de';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'wp12836029-confirmation';          // SMTP username
            $mail->Password = 'Jannosch353';                      // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 25;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('confirm@intranetproject.net', 'KIS');
            $mail->addAddress($to);               // Name is optional
            $mail->addReplyTo('support@intranetproject.net', 'Information');


            $text = $text;
            //Content

            $mail->isHTML(true);
            $mail->setLanguage("de");// Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $text;

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
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
