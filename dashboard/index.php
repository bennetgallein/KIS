<?php

include("../php/database.php");
include("../php/Notification.php");
include(dirname(__FILE__) . "/../php/User.php");

$db = new DB();
$notifications = new Notification($db);

if (!isset($_SESSION['user']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
    header("Location: ../index.php");
} else if (!isset($_SESSION['user'])) {
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: ../index.php?method=login&return=" . $actual_link);
    die();
}
$user = $_SESSION['user'];
$user = unserialize($user, array("allowed_classes" => true));

if (isset($_GET['removenotification']) && isset($_GET['id'])) {
    $idi = $_GET['id'];
    $notifications->removeNotification($user->getId(), $idi);
}

?>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/favicon.png"/>
    <link rel="icon" type="image/png" href="../assets/favicon.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title><?= $db->getConfig()['site_name'] ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
    <!-- Bootstrap core CSS     -->
    <link href="../bower_components/momentum/css/momentum.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.9.32/css/materialdesignicons.css">
    <?php
    $db->integrateCustomBootstrap();
    ?>
</head>

<body>
<div id="wrapper">
    <?php include('sidebar.php'); ?>
    <div class="website-content pb-4">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="width: 100%;">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="mdi mdi-star"></i>Dashboard</a>
                    </li>
                </ul>
                <?php include("notifications.php") ?>
            </div>
        </nav>
        <br>
        <div class="inner pl-3 pb-3 pr-3">
            <div class="row topcard col-md-12 mb-3">
                <div class="progress bg-warning mt-3" style="height: 50px;">
                    <p>This is still work in progress. If you find bugs, report them please <a
                                href="html/changelog.html">here</a>.</p>
                </div>
            </div>
            <div class="row col-md-12">
                <?php
                foreach ($db->getModules() as $module) {
                    if ($module->isActive()) {
                        if ($module->getBaseperm() <= $user->getPermissions()) {
                            foreach ($module->getDashboards() as $board) {
                                if ($board['permission'] <= $user->getPermissions()) {
                                    include($module->getPath() . "/" . $module->getBasepath() . $board['link']);
                                }
                            }
                        }
                    }
                }
                ?>
            </div>
            <?php include("footer.php"); ?>
        </div>
    </div>
</div>
</body>
<!-- Bootstrap JavaScript & jQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="../bower_components/momentum/js/morris.js"></script>
<script src="../bower_components/momentum/js/momentum.js" type="text/javascript"></script>


</html>
