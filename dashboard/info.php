<?php
include("../php/database.php");
include(dirname(__FILE__) . "/../php/User.php");

$db = new DB();
if (!isset($_SESSION['user']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
    $identifier = $_COOKIE['identifier'];
    $securitytoken = $_COOKIE['securitytoken'];
    $sql = "SELECT * FROM securitytokens WHERE identifier = '$identifier'";
    $result = $db->simpleQuery($sql);
    $row = $result->fetch_object();
    if (md5($securitytoken) !== $row->securitytoken) {
        die(' Ein vermutlich gestohlener Security Token wurde identifiziert');
    } else {
        $neuer_securitytoken = $db->random_string();
        $sql = "UPDATE securitytokens SET securitytoken = '" . md5($neuer_securitytoken) . "', created_at=NOW() WHERE identifier ='" . $identifier . "'";
        $result = $db->simpleQuery($sql);
        setcookie("identifier", $identifier, time() + (3600 * 24 * 365), "/"); //1 Jahr Gültigkeit
        setcookie("securitytoken", $neuer_securitytoken, time() + (3600 * 24 * 365), "/"); //1 Jahr Gültigkeit
        $res = $db->simpleQuery("SELECT * FROM users WHERE id='$row->user_id'");
        $data = $res->fetch_object();
        $arr = array(
            "realid" => $data->_id,
            "id" => $data->id,
            "email" => $data->email,
            "firstname" => $data->firstname,
            "lastname" => $data->lastname,
            "password" => $data->password,
            "permissions" => $data->permissions
        );

        $user = new User($arr);
        $_SESSION['user'] = serialize($user);
    }
} else if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    die();
}
$user = $_SESSION['user'];
$user = unserialize($user, array("allowed_classes" => true));


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
</head>

<body>
<div class="wrapper">
    <?php include('sidebar.php'); ?>
    <div class="main-panel">
        <nav class="navbar navbar-transparent navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Dashboard </a>
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
                if (isset($_GET['module'])) {
                    $modulea = $_GET['module'];
                    foreach ($db->getModules() as $mod) {
                        if (($mod->getName() == $modulea)) {
                            $modd = $mod;
                        }
                    }
                } else {
                    header("Location: index.php");
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" data-background-color="<?= $db->getConfig()['color']?>">
                                <h4 class="title">Info about <?= $modd->getName() ?></h4>
                            </div>
                            <div class="card-content">
                                <ul class="list-group">
                                    <li class="list-group-item"><b>Name: </b><?= $modd->getName() ?></li>
                                    <li class="list-group-item"><b>Version: </b><?= $modd->getVersion() ?></li>
                                    <li class="list-group-item"><b>Baseperm: </b><?= $modd->getBaseperm() ?></li>
                                    <li class="list-group-item"><b>Authors: </b></li>
                                    <?php foreach ($modd->getAuthors() as $author): ?>
                                        <li class="list-group-item" style="margin-left: 15px;"><b><?= $author['name'] . "</b> &lt;" . $author['email'] . "&gt;"?></li>
                                    <?php endforeach; ?>
                                    <!--<li class="list-group-item">Navs:</li>
                                    <li class="list-group-item" style="margin-left: 15px;">Icon:</li>
                                    <li class="list-group-item" style="margin-left: 15px;">Name:</li>
                                    <li class="list-group-item" style="margin-left: 15px;">Link:</li>
                                    <li class="list-group-item" style="margin-left: 15px;">Permission:</li>
                                    <li class="list-group-item" style="margin-left: 15px;">Type:</li>
                                    <li class="list-group-item">Dashboards:</li>
                                    <li class="list-group-item" style="margin-left: 15px;">Link:</li>
                                    <li class="list-group-item" style="margin-left: 15px;">Permission:</li>
                                    <li class="list-group-item">Includeables:</li>
                                    <li class="list-group-item" style="margin-left: 15px;">Name:</li>
                                    <li class="list-group-item" style="margin-left: 15px;">Link:</li>
                                    <li class="list-group-item" style="margin-left: 15px;">Permission:</li>-->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
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
<script src="../assets/js/chartist.min.js"></script>
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
<script type="text/javascript">
    $(document).ready(function () {

        // Javascript method's body can be found in assets/js/demos.js
        demo.initDashboardPageCharts();

    });
</script>

</html>