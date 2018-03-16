<?php
include("../vendor/autoload.php");
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
        //set cookies & proceed login
        $_SESSION['user'] = serialize($user);
    }
} else if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    die();
}
$user = $_SESSION['user'];
$user = unserialize($user, array("allowed_classes" => true));

// Trello
$client = new \Trello\Client("fa84a9d1f4a184d02577164ef2bea5a8");
$client->setAccessToken("a866af24236b9097268619365e8e55db13b0e7f2f81b0d5a0eb5eb9ee8cd16c4");
$board = $client->getBoard("5a6f8dbaad2a46b8f2231793");
$cardtag = "";
foreach ($board->getCards() as $card) {
    $idList = $card->__get("idList");
    if ($idList == "5a6f8dbfe41f1d8c9156b56e") {
        $url = $card->__get("shortUrl");
        $cardtag .= '<div style="margin: 1%; float: left; min-height: 210px;" ><blockquote class="trello-card">
                    <a href="' . $url . '">Trello Card</a>
                </blockquote></div>';
    }
}

if (isset($_GET['a'])) {
    if (isset($_POST['bug']) && isset($_POST['title'])) {
        //$client->authenticate('fa84a9d1f4a184d02577164ef2bea5a8', 'a866af24236b9097268619365e8e55db13b0e7f2f81b0d5a0eb5eb9ee8cd16c4', Client::AUTH_URL_CLIENT_ID);
        // board id: 5a5a88bd0682dea8ba33dde6
        // list id: 5a6e3737021462be3b0f1fd0
        $card = new \Trello\Model\Card($client);
        $card->name = $_POST['title'];
        $card->desc = $_POST['bug'] . " - " . $user->getName();
        $card->idList = "5a6f8dbfe41f1d8c9156b56e";
        $card->idLabels = "5a6f8dba9ae3d60b0c1a9319,5a6f8dba9ae3d60b0c1a931a";
        $card->pos = "top";
        if (isset($_POST['important'])) {
            $card->idMembers = "5966a84bc25aa22b76401617,57dadec7c9c6672938c9f085";
        }
        $card->save();
        $_SESSION['bug_reported'] = "ee";
        unset($_POST);
        header("Location: changelog.php");
    }

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
                    <a class="navbar-brand" href="#">Changelog </a>
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

                if (isset($_SESSION['bug_reported'])) {
                    echo '<div class="card">
                        <div class="card-header" data-background-color="' . $db->getConfig()['color'] . '">
                            <h4 class="title">Danke!</h4>
                            <p class="category">Danke, vielleicht beheben wir den Fehler wenn es einen gibt!</p>
                        </div>
                    </div>';
                    unset($_SESSION['bug_reported']);
                }

                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                                <h4 class="title"><?= $db->m("changelog_report_title") ?></h4>
                                <p class="category"><?= $db->m("changelog_report_desc") ?></p>
                            </div>
                            <div class="card-content">
                                <form action="changelog.php?a=report" method="post">
                                    <input placeholder="Title" name="title" value="" type="text" class="form-control">
                                    <textarea type="text" name="bug" class="form-control"
                                              placeholder="<?= $db->m("changelog_report_textarea") ?>"
                                              id="comment" rows="5"></textarea>
                                    <div class="form-check pull-left" style="padding-bottom: 15px; font-size: 1.6em;">
                                        <input class="form-check-input" name="important" type="checkbox" value=""
                                               id="defaultCheck1">
                                        <label class="form-check-label" for="defaultCheck1">
                                            <?= $db->m("changelog_report_importance") ?>
                                        </label>
                                    </div>
                                    <button type="submit" data-background-color="<?= $db->getConfig()['color'] ?>"
                                            class="btn btn-primary pull-right"><?= $db->m("changelog_report_submit") ?>
                                    </button>
                                    <div class="clearfix"></div>
                                </form>
                                <div style="overflow: auto; width: 100%; display: inline-block">
                                    <?= $cardtag ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="https://p.trellocdn.com/embed.min.js"></script>
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
