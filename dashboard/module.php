<?php
if (!isset($_GET['module'])) {
    header("Location: index.php");
    die();
}
include("../php/database.php");
include(dirname(__FILE__) . "/../php/User.php");

$db = new DB();

if (!isset($_SESSION['user']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
    header("Location: ../index.php");
} else if (!isset($_SESSION['user'])) {
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: ../index.php?method=login&return=" . $actual_link);
    die();
}
$params = new stdClass();
if (isset($_GET['params'])) {
    $requestpath = $_SERVER['REQUEST_URI'];
    $aparam = explode("_", $_GET['params']);
    foreach ($aparam as $param) {
        $keyvalue = explode("|", $param);
        $key = $keyvalue[0];
        $value = $keyvalue[1];
        $params->$key = $value;
    }
}
$user = $_SESSION['user'];
$user = unserialize($user);
$amodule = $_GET['module'];
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
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet"/>
    <!--  Material Dashboard CSS    -->
    <link href="../assets/css/material-dashboard.css?v=1.2.0" rel="stylesheet"/>
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="../assets/css/demo.css" rel="stylesheet"/>
    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet'
          type='text/css'>
    <link href="../assets/css/404.css" rel="stylesheet">
</head>

<body>
<div class="wrapper">
    <?php include('sidebar.php'); ?>
    <div class="main-panel">
        <nav class="navbar navbar-transparent navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only"><?= $db->m("dash_togglenav") ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <?php include "notifications.php" ?>
                        <li>
                            <a href="user.php">
                                <i class="material-icons">person</i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
                <?php
                $r = false;
                foreach ($db->getModules() as $module) {
                    if ($module->isActive()) {
                        $moddd = $module;
                        foreach ($module->getNavs() as $dashboard) {
                            if ($module->getBasepath() . $dashboard['link'] == $amodule) {
                                $aamod = $module->getName();
                                if ($user->getPermissions() >= $dashboard['permission']) {
                                    $r = @include($module->getPath() . "/" . $amodule);
                                    if (!$r) {
                                        echo '<div class="notfound"><div class="notfoundtext">404 not found!</div></div>';
                                    }
                                } else {
                                    echo '<div class="notfound"><div class="notfoundtext">401 not authenticated!</div></div>';
                                }
                            }
                        }
                    }
                }
                if (!$r) {
                    echo '<div class="notfound"><div class="notfoundtext">404 not found!</div></div>';
                }
                ?>
            </div>
            <?php include("footer.php"); ?>
        </div>
    </div>
</div>
</body>
<!--   Core JS Files   -->
<script src="../assets/js/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../assets/js/material.min.js" type="text/javascript"></script>
<!--  Charts Plugin -->
<!--<script src="../assets/js/chartist.min.js"></script>-->
<!--  Dynamic Elements plugin -->
<script src="../assets/js/arrive.min.js"></script>
<!--  PerfectScrollbar Library -->
<script src="../assets/js/perfect-scrollbar.jquery.min.js"></script>
<!--  Notifications Plugin    -->
<script src="../assets/js/bootstrap-notify.js"></script>
<!-- Material Dashboard javascript methods -->
<script src="../assets/js/material-dashboard.js?v=1.2.0"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="../assets/js/demo.js"></script>

</html>
<?php
$db->disconnect();
?>
