<?php
/**
 * Created by PhpStorm.
 * User: Bennet
 * Date: 1/14/2018
 * Time: 9:33 PM
 */
session_destroy();

$_SESSION = [];

setcookie("identifier", null, -1, "/");
setcookie("securitytoken", null, -1, "/");

header("Location: ../index.php");