<?php
/**
 * Created by PhpStorm.
 * User: Bennet
 * Date: 1/16/2018
 * Time: 7:09 PM
 */
if (!isset($_SESSION['user'])) {
    header("Location ../index.php");
}
include("database.php");
include("User.php");
$db = new DB();

$user = unserialize($_SESSION['user']);
if (isset($_GET['method'])) {
    $method = $_GET['method'];

    if ($method != "delete" && $method != "resetpw") {
        header("Location: ../dashboard/index.php");
    }
    if (isset($_GET['confirmed'])) {
        if ($method == "delete") {
            $res = $db->simpleQuery("DELETE FROM users WHERE id='" . $user->getId() . "'");
            var_dump($res);
            header("Location: ../index.php");
            die();
        }
    }
    if ($method == "delete") {
        header("Location: ../dashboard/user.php?confirm=delete");
    }
    if ($method == "resetpw") {
        header("Location: ../dashboard/user.php?confirm=resetpw");
    }

    if (isset($_GET['confirmed'])) {
        if ($method == "delete") {
            $res = $db->simpleQuery("DELETE * FROM users WHERE id='" . $user->getId() . "'");
            var_dump($res);
            //header("Location: ../index.php");
            die();
        }
    }
} else {
    header("Location: ../dashboard/index.php");
}