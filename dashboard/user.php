<?php
include("../php/database.php");
include(dirname(__FILE__) . "/../php/User.php");
include("../php/Notification.php");

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

$res = $db->simpleQuery("SELECT * FROM adresses WHERE userid='" . $user->getId() . "' LIMIT 1");
if ($res) {
    if ($res->num_rows == 1) {
        $data = $res->fetch_object();
    }
} else {
    $data = array();
    header("Location: ../index.php?method=login&error=internal");
}
if (isset($_GET['changelang'])) {
    setcookie('lang', $_GET['changelang']);
    header("Location: user.php");
}
if ($user->getEmail() != "test@test.de") {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_GET['update'])) {
            if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['repeatemail']) && isset($_POST['adress']) && isset($_POST['city']) && isset($_POST['country']) && isset($_POST['postalcode'])) {
                $firstname = $db->getConnection()->escape_string(strip_tags($_POST['firstname']));
                $lastname = $db->getConnection()->escape_string(strip_tags($_POST['lastname']));
                $email = $db->getConnection()->escape_string(strip_tags($_POST['email']));
                $repemail = $db->getConnection()->escape_string(strip_tags($_POST['repeatemail']));
                $adress = $db->getConnection()->escape_string(strip_tags($_POST['adress']));
                $company = $db->getConnection()->escape_string(strip_tags(isset($_POST['company']) ? $_POST['company'] : ""));
                $city = $db->getConnection()->escape_string(strip_tags($_POST['city']));
                $country = $db->getConnection()->escape_string(strip_tags($_POST['country']));
                $postalcode = $db->getConnection()->escape_string(strip_tags($_POST['postalcode']));

                if (!isset($_GET['token'])) {
                    echo "No token provided! It's likely you clicked on a link where the token parameter was removed as an attemp to harm your account. Be careful!";
                    die();
                }
                if (!$db->checkCSRFToken($_GET['token'])) {
                    echo "CSRF Token do not match!";
                    die();
                }
                if (!($email == $repemail)) {
                    header("Location: user.php?error=Email%20do%20not%20match");
                    die();
                }
                $res = $db->simpleQuery("SELECT * FROM users WHERE email='" . $email . "'");
                if ($res->num_rows >= 1 && $user->getEmail() != $email) {
                    header("Location: user.php?error=Email%20already%20in%20system!");
                    die();
                }
                if (isset($data->adress)) {
                    // update
                    $res = $db->prepareQuery("UPDATE adresses SET adress=?, company=?, city=?, country=?, postalcode=? WHERE userid='" . $user->getId() . "'", array($adress, $company, $city, $country, $postalcode));
                    header("Location: user.php?success=1");
                } else {
                    $res = $db->prepareQuery("INSERT INTO adresses(userid, adress, company, city, country, postalcode) VALUES (?,?,?,?,?,?)", array($user->getId(), $adress, $company, $city, $country, $postalcode));
                    if (!$res) {
                        header("Location: user.php?error=1");
                        die();
                    }
                }
                $res = $db->prepareQuery("UPDATE users SET firstname=?, lastname=?, email=? WHERE id='" . $user->getId() . "'", array($firstname, $lastname, $email));
                if ($res) {
                    $user->setFirstname($firstname);
                    $user->setLastname($lastname);
                    $user->setEmail($email);
                    $_SESSION['user'] = serialize($user);
                    header("Location: user.php?success=1");
                    die();
                } else {
                    header("Location: user.php?error=2");
                    die();
                }
                $db->prepareQuery("INSERT INTO notifications (userid, message) VALUES (?, ?)", array(
                    $db->escape($id), $db->escape("You updated your profile!")
                ));
            } else {
            }
        }
    }
}
if (isset($_GET['design'])) {
    if ($_GET['design'] == "none") {
        setcookie('custombootstrap', "", time()+60*60*24*30, "/");
    } else {
        error_log("setting custom design");
        setcookie('custombootstrap', $_GET['design'], time()+60*60*24*30, "/");
    }
    header("Location: user.php");
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
                        <a class="nav-link" href="#"><i class="mdi mdi-star"></i><?= $db->m("sidebar_profile") ?></a>
                    </li>
                </ul>
                <?php include("notifications.php") ?>
            </div>
        </nav>
        <br>
        <div class="inner pl-3 pb-3 pr-3">
            <div class="row col-md-12 mb-3 topcard">
                <div class="card col-md-12">
                    <div class="card-body">
                        <h5 class="card-title"><?= $db->m("profile_edit_profile") ?></h5>
                        <small>
                            <?= $db->m("profile_edit_complete") ?>!
                        </small>
                        <p class="card-text">
                        <form action="user.php?update=1&token=<?= $_SESSION['csrftoken'] ?>" method="post">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fName"><?= $db->m("profile_edit_firstname") ?></label>
                                    <input type="text" name="firstname" class="form-control" id="fName"
                                           value="<?= $user->getFirstname() ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lName"><?= $db->m("profile_edit_lastname") ?></label>
                                    <input type="text" name="lastname" class="form-control" id="lName"
                                           value="<?= $user->getLastname() ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email"><?= $db->m("profile_edit_email") ?></label>
                                    <input type="email" name="email" class="form-control" id="email"
                                           value="<?= $user->getEmail() ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="conf"><?= $db->m("profile_edit_confirmemail") ?></label>
                                    <input type="email" name="repeatemail" class="form-control" id="conf"
                                           value="<?= $user->getEmail() ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <label for="address"><?= $db->m("profile_edit_addresss") ?></label>
                                    <input type="text" name="adress" class="form-control" id="address"
                                           value="<?= (isset($data->adress) ? $data->adress : "") ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="comp"><?= $db->m("profile_edit_company") ?></label>
                                    <input type="text" name="company" class="form-control" id="comp"
                                           value="<?= isset($data->company) ? $data->company : "" ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="city"><?= $db->m("profile_edit_city") ?></label>
                                    <input type="text" name="city" class="form-control" id="city"
                                           value="<?= isset($data->city) ? $data->city : "" ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="country"><?= $db->m("profile_edit_country") ?></label>
                                    <input type="text" name="country" class="form-control" id="country"
                                           value="<?= isset($data->country) ? $data->country : "" ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="postcode"><?= $db->m("profile_edit_postalcode") ?></label>
                                    <input type="text" name="postalcode" class="form-control" id="postcode"
                                           value="<?= isset($data->postalcode) ? $data->postalcode : "" ?>">
                                </div>
                            </div>
                            <button type="submit"
                                    class="btn btn-primary col-md-2 float-right"><?= $db->m("profile_edit_submit") ?></button>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
            <?php
            if (isset($_GET['confirm'])):
                $toConfirm = $_GET['confirm'];
                if ($toConfirm == "delete"): ?>
                    <a href="../php/userbtn.php?method=delete&confirmed=1"><?= $db->m("profile_delete_confirm") ?></a>
                <?php elseif ($toConfirm == "resetpw"): ?>
                    <a href="../php/userbtn.php?method=resetpw&confirmed=1"><?= $db->m("profile_reset_confirm") ?></a>
                <?php endif;
            endif;
            ?>
            <div class="row col-md-12">
                <div class="card col-md-12">
                    <div class="card-body">
                        <p class="card-text">
                        <div class="dropdown">
                            <a class="dropdown-toggle nocaretdrop btn btn-primary" href="#" role="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-bell mdi-light mdi-24px"></i>Hello</a>

                            <div class="dropdown-menu" aria-labelledby="notification">
                                <?php
                                foreach ($db->getSupportedLangs() as $key => $lang) {
                                    echo "<a class='dropdown-item' href='user.php?changelang=" . $lang . "'>" . $key . "</a>";
                                }?>
                            </div>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row col-md-12 mt-3">
                <div class="card col-md-12">
                    <div class="card-body">
                        <h5 class="card-title bg-danger text-center text-white pt-3 pr-3 pb-3 pl-3">DANGER ZONE</h5>
                        <p class="card-text">
                            <a href="../php/userbtn.php"
                               class="btn btn-info col-md-4 offset-md-1"><?= $db->m("profile_danger_password_title") ?><br>
                                <small><?= $db->m("profile_danger_password_desc") ?></small>
                            </a>
                            <a href="../php/userbtn.php?method=resetpw&confirmed=1"
                               class="btn btn-info col-md-4 offset-md-2"><?= $db->m("profile_danger_delete_title") ?>
                                <br>
                                <small><?= $db->m("profile_danger_delete_desc") ?></small>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <?php if ($db->getConfig()['customstyle']): ?>
            <div class="row col-md-12 mt-3">
                <div class="card col-md-12">
                    <div class="card-body">
                        <h5 class="card-title"><?= $db->m("profile_custom_style_title") ?></h5>
                        <a href="user.php?token=<?= $_SESSION['csrftoken'] ?>&design=none">Default</a>
                        <p class="card-text">
                            <?= $db->m("profile_custom_style_desc") ?>
                            <div class='row'>
                            <?php

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_URL, 'https://bootswatch.com/api/4.json');
                            $result = curl_exec($ch);
                            curl_close($ch);

                            $styles = json_decode($result);
                            $x = 0;
                            foreach ($styles->themes as $style) {
                                echo "<div class='col-md-4 mt-3'>";
                                echo "<a href='user.php?token=" . $_SESSION['csrftoken'] . "&design=" . ($style->cssCdn) . "'>";
                                echo "<img style='padding-right: 0; padding-left: 0;' class='col-md-10 offset-md-1 border border-dark' src='" . $style->thumbnail . "'>";
                                echo "</a><br>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php include("footer.php") ?>
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
