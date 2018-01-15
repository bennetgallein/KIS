<?php
/**
 * Created by PhpStorm.
 * User: Bennet
 * Date: 1/14/2018
 * Time: 9:33 PM
 */
session_destroy();
unset($_SESSION);
header("Location: ../index.php");