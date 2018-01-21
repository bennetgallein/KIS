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
    header("Location: ../index.php");
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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_GET['update'])) {
        if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['repeatemail']) && isset($_POST['adress']) && isset($_POST['city']) && isset($_POST['country']) && isset($_POST['postalcode'])) {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $repemail = $_POST['repeatemail'];
            $adress = $_POST['adress'];
            $company = isset($_POST['company']) ? $_POST['company'] : "";
            $city = $_POST['city'];
            $country = $_POST['country'];
            $postalcode = $_POST['postalcode'];

            if (!($email == $repemail)) {
                header("Location: user.php?error=Email%20do%20not%20match");
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
                    <a class="navbar-brand" href="#"> Profile </a>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                                <h4 class="title">Edit Profile</h4>
                                <p class="category">Complete your profile</p>
                            </div>
                            <div class="card-content">
                                <form action="user.php?update=1" method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group label-floating">
                                                <label class="control-label">First Name</label>
                                                <input name="firstname" value="<?= $user->getFirstname() ?>" type="text"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Last Name</label>
                                                <input name="lastname" value="<?= $user->getLastname() ?>" type="text"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Email address</label>
                                                <input name="email" value="<?= $user->getEmail() ?>" type="email"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Confirm Email address</label>
                                                <input name="repeatemail" value="<?= $user->getEmail() ?>" type="email"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Adress</label>
                                                <input name="adress"
                                                       value="<?= (isset($data->adress) ? $data->adress : "") ?>"
                                                       type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Company</label>
                                                <input name="company"
                                                       value="<?= isset($data->company) ? $data->company : "" ?>"
                                                       type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group label-floating">
                                                <label class="control-label">City</label>
                                                <input name="city"
                                                       value="<?= isset($data->city) ? $data->city : "" ?>"
                                                       type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Country</label>
                                                <input name="country"
                                                       value="<?= isset($data->country) ? $data->country : "" ?>"
                                                       type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Postal Code</label>
                                                <input name="postalcode"
                                                       value="<?= isset($data->postalcode) ? $data->postalcode : "" ?>"
                                                       type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" data-background-color="<?= $db->getConfig()['color'] ?>"
                                            class="btn btn-primary pull-right">Update Profile
                                    </button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($_GET['confirm'])):
                    $toConfirm = $_GET['confirm'];
                    if ($toConfirm == "delete"): ?>
                        <a href="../php/userbtn.php?method=delete&confirmed=1">Confirm account deletion</a>
                    <?php elseif ($toConfirm == "resetpw"): ?>
                        <a href="../php/userbtn.php?method=resetpw&confirmed=1">Confirm account deletion</a>
                    <?php endif; endif; ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" data-background-color="red">
                                <h4 class="title">Danger Zone</h4>
                                <p class="category">The stuff you have to confirm twice.</p>
                            </div>
                            <div class="card-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a data-background-color="<?= $db->getConfig()['color'] ?>"
                                           class="btn btn-primary pull-left col-md-5" href="../php/userbtn.php">
                                            Reset Password<br>
                                            <small>This will send you a randomly generated password to your email.
                                            </small>
                                        </a>
                                        <a data-background-color="<?= $db->getConfig()['color'] ?>"
                                           class="btn btn-primary pull-right col-md-5"
                                           href="../php/userbtn.php?method=delete">
                                            Delete Account<br>
                                            <small>This will permanently delete your account!</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("footer.php"); ?>
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

</html>
