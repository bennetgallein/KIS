<?php
include("../vendor/autoload.php");
include("../php/database.php");
include(dirname(__FILE__) . "/../php/User.php");

$db = new DB();

if (!isset($_SESSION['user']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
    header("Location: ../index.php");
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.9.32/css/materialdesignicons.css">
    <link rel="stylesheet" href="../bower_components/momentum/css/momentum.css">
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
                        <a class="nav-link" href="#"><i class="mdi mdi-star"></i><?= $db->m("sidebar_changelog") ?></a>
                    </li>
                </ul>
                <?php include("notifications.php") ?>
            </div>
        </nav>
        <br>
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
                <div class="inner pl-3 pb-3 pr-3">
                    <div class="row col-md-12 mb-3 topcard">
                        <div class="card col-md-12">
                            <div class="card-body">
                                <h5 class="card-title"><?= $db->m("changelog_report_title")?></h5>
                                <small><?= $db->m("changelog_report_desc") ?></small>
                                <p class="card-text">
                                    <form action="changelog.php?a=report" method="post">
                                        <div class="form-group">
                                            <input type="text" name="title" class="form-control" id="bugTitle" placeholder="<?= $db->m("changelog_report_bugtitle")?>">
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="bug" id="bugText" rows="3" placeholder="<?= $db->m("changelog_report_textarea")?>"></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="form-check col-md-8">
                                                <input name="important" class="form-check-input" type="checkbox" value="" id="importantCheck">
                                                <label class="form-check-label" for="importantCheck"><?= $db->m("changelog_report_importance") ?></label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="submit" class="btn btn-primary float-right" value="<?= $db->m("changelog_report_submit") ?>" />
                                            </div>
                                        </div>
                                    </form>
                                    <div style="overflow: auto; width: 100%; display: inline-block">
                                    <?= $cardtag ?>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="https://p.trellocdn.com/embed.min.js"></script>
            <?php include("footer.php"); ?>
        </div>
    </div>
</body>
</html>
