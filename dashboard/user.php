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
        //set cookies & proceed login
        $_SESSION['user'] = serialize($user);
    }
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
            }
        }
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
    <link href="../bower_components/momentum/css/momentum.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.9.32/css/materialdesignicons.css">
</head>

<body>
<div id="wrapper" class="">
    <?php include('sidebar.php'); ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="mdi mdi-star"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">one</a>
                            <a class="dropdown-item" href="#">two</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">three</a>
                        </div>
                    </li>

                </ul>
                <a href="#" class="ml-3"><i class="mdi mdi-bell mdi-light mdi-24px"></i></a>
                <a href="html/profile.html" class="ml-3"><i class="mdi mdi-account mdi-light mdi-24px"></i></a>
            </div>
        </nav>
        <div class="website-content pb-4">
            <div class="inner pl-3 pb-3 pr-3">
                <div class="row col-md-12 mb-3">
                    <div class="card col-md-12">
                        <div class="card-body">
                            <h5 class="card-title"><?= $db->m("profile_edit_profile") ?></h5>
                            <p>
                                <?= $db->m("profile_edit_complete") ?>
                            </p>
                            <p class="card-text">
                                <form action="user.php?update=1" method="post">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="fName"><?= $db->m("profile_edit_firstname")?></label>
                                            <input type="text" class="form-control" id="fName" value="<?= $user->getFirstname() ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="lName"><?= $db->m("profile_edit_lastname")?></label>
                                            <input type="text" class="form-control" id="lName" value="<?= $user->getLastname() ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="email"><?= $db->m("profile_edit_email") ?></label>
                                            <input type="email" class="form-control" id="email" value="<?= $user->getEmail() ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="conf"><?= $db->m("profile_edit_confirmemail") ?></label>
                                            <input type="email" class="form-control" id="conf" value="<?= $user->getEmail() ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <label for="address"><?= $db->m("profile_edit_addresss") ?></label>
                                            <input type="text" class="form-control" id="address" value="<?= (isset($data->adress) ? $data->adress : "") ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="comp"><?= $db->m("profile_edit_company") ?></label>
                                            <input type="text" class="form-control" id="comp" value="<?= isset($data->company) ? $data->company : "" ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="city"><?= $db->m("profile_edit_city") ?></label>
                                            <input type="text" class="form-control" id="city" value="<?= isset($data->city) ? $data->city : "" ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="country"><?= $db->m("profile_edit_country") ?></label>
                                            <input type="text" class="form-control" id="country" value="<?= isset($data->country) ? $data->country : "" ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="postcode"><?= $db->m("profile_edit_postalcode") ?></label>
                                            <input type="text" class="form-control" id="postcode" value="<?= isset($data->postalcode) ? $data->postalcode : "" ?>">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary col-md-2 float-right"><?= $db->m("profile_edit_submit")?></button>
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
                    <?php endif; endif; ?>
                <div class="row col-md-12">
                    <div class="card col-md-12">
                        <div class="card-body">
                            <h5 class="card-title bg-danger text-center text-white pt-3 pr-3 pb-3 pl-3">DANGER ZONE</h5>
                            <p class="card-text">
                                <a href="../php/userbtn.php" class="btn btn-info col-md-4 offset-md-1"><?= $db->m("profile_danger_title") ?><br><small><?= $db->m("profile_danger_password_desc") ?></small></a>
                                <a href="../php/userbtn.php?method=resetpw&confirmed=1" class="btn btn-info col-md-4 offset-md-2"><?= $db->m("profile_danger_delete_title")?><br><small><?= $db->m("profile_danger_delete_desc") ?></small></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</body>
<!-- Bootstrap JavaScript & jQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="../bower_components/momentum/js/morris.js"></script>
<script src="../bower_components/momentum/js/momentum.js" type="text/javascript"></script>

</html>
