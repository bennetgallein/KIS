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
    <?php
    $db->integrateCustomBootstrap();
    ?>
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.9.32/css/materialdesignicons.css">
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
        <div class="website-content pb-4">
            <div class="inner pl-3 pb-3 pr-3">
                <div class="row col-md-12 mb-3">
                    <div class="card col-md-12">
                        <div class="card-body">
                            <h5 class="card-title">Tweak your settings</h5>
                            <p class="card-text">
                                <form>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="Name">Interface Name (Top Left)</label>
                                            <input type="text" class="form-control" id="Name" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="lName">Last Name</label>
                                            <input type="text" class="form-control" id="lName" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="semail">Support email address</label>
                                            <input type="email" class="form-control" id="semail" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-group mb-3 col-md-6">
                                            <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="checkbox">
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" value="Activate Bootswatch Styles" readonly>
                                        </div>
                                        <div class="input-group mb-3 col-md-6">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <input type="checkbox">
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" value="Activate Custom CSS" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="text1">Text</label>
                                            <input type="text" class="form-control" id="text1" placeholder="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="text2">Text</label>
                                            <input type="text" class="form-control" id="text2" placeholder="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="text3">Text</label>
                                            <input type="text" class="form-control" id="text3" placeholder="">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary col-md-2 float-right">Update Settings</button>
                                </form>
                            </p>
                        </div>
                    </div>
                </div>
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