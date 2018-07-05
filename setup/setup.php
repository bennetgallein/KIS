<?php
include("../php/database.php");
$db = new DB();

if (file_exists(dirname(__FILE__) . "/../config.json")) {
    $config = json_decode(file_get_contents(dirname(__FILE__) . "/../config.json"), true);
    if ($db->getConnection()->connect_errno == 1049):
        ?>
        <form action="setup.php" method="post">
            <input type="text" name="license" placeholder="License Key" />
            <br>
            <br>
            <input type="text" name="email" placeholder="Admin email" />
            <input type="password" name="password" placeholder="Admin Password" />
            <input type="password" name="password2" placeholder="confirm Admin Password" />
            <input type="submit"/>
        </form>
        <?php
    else:
        header("Location: ../index.php");
    endif;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['license']) && !empty($_POST['license']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['password2']) && !empty($_POST['password2'])) {
        $config = $db->getConfig();
        $db = new MySQLi($db->getConfig()["database"][0]["host"], $db->getConfig()['database'][0]['user'], $db->getConfig()['database'][0]['password']);

        if ($_POST['password'] != $_POST['password2']) {
            echo "Passwords don't match!";
            exit();
        }

        $email = $db->real_escape_string($_POST['email']);
        $password = $db->real_escape_string($_POST['password']);

        $red = "#FF0000";
        $green = "#2fbc2f";

        //$response = httpGet("https://kis.intranetproject.net/checklicense.php?license=" . $_POST['license']);
        //$response = json_decode($response);

        //if ($response->valid == true) {
            echo "<p style='color: " . $green . "'>License is valid! Continue setup</p>";
            echo "<p>==============================================</p>";
            echo "<p>Starting creation of Database</p>";
            echo "<p>==============================================</p>";


            $sql = "CREATE DATABASE IF NOT EXISTS " . $config['database'][0]['database'];
            $res = $db->query($sql);
            if ($res) {
                echo "<p style='color: " . $green . "'>Successfully create Database.</p>";
            } else {
                echo "<p style='color: " . $red . "'>Failed to create Database!</p>";
                exit();
            }
            echo "<p>==============================================</p>";
            $db->select_db($config['database'][0]['database']);
            echo "<p>==============================================</p>";
            echo "Creating table 'users'";
            $sql = "CREATE TABLE IF NOT EXISTS `users` (
`_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` tinytext COLLATE latin1_german2_ci,
  `email` tinytext COLLATE latin1_german2_ci,
  `firstname` tinytext COLLATE latin1_german2_ci,
  `lastname` tinytext COLLATE latin1_german2_ci,
  `password` tinytext COLLATE latin1_german2_ci,
  `permissions` int(11) DEFAULT '1',
  `registered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (_id)
);";
            $res = $db->query($sql);
            if ($res) {
                echo "<p style='color: " . $green . "'>Successfully create table 'users'.</p>";
            } else {
                echo "<p style='color: " . $red . "'>Failed to create table 'users'!</p>";
            }
            echo "<p>==============================================</p>";
            $db->select_db($config['database'][0]['database']);
            echo "<p>==============================================</p>";
            echo "Creating table 'adresses'";
            $sql = "CREATE TABLE IF NOT EXISTS`adresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` tinytext COLLATE latin1_german2_ci,
  `adress` tinytext COLLATE latin1_german2_ci,
  `company` tinytext COLLATE latin1_german2_ci,
  `city` tinytext COLLATE latin1_german2_ci,
  `country` tinytext COLLATE latin1_german2_ci,
  `postalcode` tinytext COLLATE latin1_german2_ci,
  PRIMARY KEY (id)
);";
            $res = $db->query($sql);
            if ($res) {
                echo "<p style='color: " . $green . "'>Successfully create table 'adresses'.</p>";
            } else {
                echo "<p style='color: " . $red . "'>Failed to create table 'adresses'!</p>";
            }
            echo "<p>==============================================</p>";
            echo "<p>==============================================</p>";
            echo "Creating table 'securitytokens'";
            $sql = "CREATE TABLE IF NOT EXISTS `securitytokens` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `securitytoken` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);";
            $res = $db->query($sql);
            if ($res) {
                echo "<p style='color: " . $green . "'>Successfully create table 'securitytokens'.</p>";
            } else {
                echo "<p style='color: " . $red . "'>Failed to create table 'securitytokens'!</p>";
            }
            echo "<p>==============================================</p>";
            echo "<p>==============================================</p>";
            echo "Creating table 'vertification_tokens'";
            $sql = "CREATE TABLE IF NOT EXISTS `vertification_tokens` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `usermail` varchar(100) COLLATE ascii_bin DEFAULT NULL,
  `token` varchar(5) COLLATE ascii_bin DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY vertification_tokens_id_uindex (id)
);";
            $res = $db->query($sql);
            if ($res) {
                echo "<p style='color: " . $green . "'>Successfully create table 'vertification_tokens'.</p>";
            } else {
                echo "<p style='color: " . $red . "'>Failed to create table 'vertification_tokens'!</p>";
            }
            echo "<p>==============================================</p>";
            echo "<p>==============================================</p>";
            echo "Creating table 'notifications'";
            $sql = "CREATE TABLE IF NOT EXISTS `notifications` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(100) COLLATE ascii_bin DEFAULT NULL,
  `message` varchar(1000) COLLATE ascii_bin DEFAULT NULL,
  `inserted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isread` tinyint(1) DEFAULT '0',
  PRIMARY KEY (id)
);";
            $res = $db->query($sql);
            if ($res) {
                echo "<p style='color: " . $green . "'>Successfully create table 'notifications'.</p>";
            } else {
                echo "<p style='color: " . $red . "'>Failed to create table 'notifications'!</p>";
            }
            echo "<p>==============================================</p>";
            echo "<p>==============================================</p>";
            echo "<p>==============================================</p>";
            echo "Creating Admin account";
            $sql = "INSERT INTO users (email, password, permissions, verified) VALUES ('" . $email . "', '" . password_hash($password, PASSWORD_DEFAULT) . "', 3, 1)";
            $res = $db->query($sql);
            $sql = "select LAST_INSERT_ID()";
            $lastid = $db->query($sql);
            $lastidob = $lastid->fetch_assoc();
            $id = $lastidob['LAST_INSERT_ID()'];
            $sql = "UPDATE users SET id='K-" . $id . "' WHERE email='" . $email . "'";
            $res2 = $db->query($sql);
            if ($res && $res2) {
                echo "<p style='color: " . $green . "'>Successfully created admin account.</p>";
            } else {
                echo "<p style='color: " . $red . "'>Failed to create admin account!</p>";
            }
            echo "<p>==============================================</p>";
            echo "<b>Setup complete. You might want to login now.<a href='../'>LOGIN</a></b>";
        //} else {
        //    echo "<p style='color: " . $red . "'>License is invalid! Discard all changes and returns to main Website!</p>";
        //}
    } else {
        echo "Please fill every field!";
    }
}
function httpGet($url) {
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

    $output=curl_exec($ch);

    curl_close($ch);
    return $output;
}
