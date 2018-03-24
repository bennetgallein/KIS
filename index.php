<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";

require 'php/database.php';
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
        $sql = "UPDATE securitytokens SET securitytoken = '" . md5($neuer_securitytoken) . "' WHERE identifier ='" . $identifier . "'";
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
        include(dirname(__FILE__) . "/php/User.php");
        $user = new User($arr);

        $token = $db->random_string();
        $_SESSION['csrftoken'] = $token;

        $_SESSION['user'] = serialize($user);
        header("Location: dashboard/index.php");
    }
}
if (isset($_SESSION['user'])) {
    header("Location: dashboard/index.php");
    die();
}
if (!(isset($_GET['method']))) {
    header("Location: index.php?method=login");
    die();
}
if (!($_GET['method'] == 'login' || $_GET['method'] == 'register') || $_GET['method'] == 'confirm') {
    header("Location: index.php?method=login");
    die();
}
$method = $_GET['method'];

if (isset($_GET['continue_login']) && isset($_GET['method'])) {
    if (!(isset($_POST['email']) && isset($_POST['pw']))) {
        //header("Location: index.php?method=login&error=fill");
        echo "email: " . $_POST['email'] . "<br>";
        echo "pw: " . $_POST['pw'];
        die();
    }
    if (!($_GET['method'] == "login")) {
        header("Location: index.php");
        die();
    }
    $email = $_POST['email'];
    $pw = $_POST['pw'];

    $res = $db->simpleQuery("SELECT * FROM users WHERE email='" . $db->escape($email) . "' LIMIT 1");
    if (!$res) {
        header("Location: index.php?method=login&error=internal");
        die();
    }
    if ($res->num_rows == 0) {
        header("Location: index.php?method=login&error=wrong");
        die();
    }
    $data = $res->fetch_object();
    if (!password_verify($pw, $data->password)) {
        header("Location: index.php?method=login&error=wrong");
        die();
    }
    if ($data->verified == '0') {
        header("Location: confirm.php");
        die();
    }
    $arr = array(
        "realid" => $data->_id,
        "id" => $data->id,
        "email" => $data->email,
        "firstname" => $data->firstname,
        "lastname" => $data->lastname,
        "password" => $data->password,
        "permissions" => $data->permissions
    );
    include("php/User.php");
    $user = new User($arr);
    //set cookies & proceed login
    $token = $db->random_string();
    $_SESSION['csrftoken'] = $token;
    
    $_SESSION['user'] = serialize($user);
    if (isset($_POST['stay'])) {
        $identifier = $db->random_string();
        $securitytoken = $db->random_string();
        $sql = "INSERT INTO securitytokens (user_id, identifier, securitytoken) VALUES ('" . $user->getId() . "', '$identifier', '" . md5($securitytoken) . "')";
        $result = $db->simpleQuery($sql);
        setcookie("identifier", $identifier, time() + (3600 * 24 * 365), "/"); //1 Jahr Gültigkeit
        setcookie("securitytoken", $securitytoken, time() + (3600 * 24 * 365), "/"); //1 Jahr Gültigkeit
    }
    if (isset($_GET['return'])) {
        header("Location:" . $_GET['return']);
    } else {
        header("Location: dashboard/index.php");
    }
    die();
}
if (isset($_GET['continue_registration']) && isset($_GET['method'])) {
    if (!(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['pw1']) && isset($_POST['pw2']))) {
        header("Location: index.php?method=register&error=fill");
        die();
    }
    if (trim($_POST['firstname']) && trim($_POST['lastname']) && trim($_POST['email']) && trim($_POST['pw1']) && trim($_POST['pw1'])) {

        if (!($_GET['method'] == "register")) {
            header("Location: index.php");
            die();
        }
        $firstname = strip_tags($_POST['firstname']);
        $lastname = strip_tags($_POST['lastname']);
        $email = strip_tags($_POST['email']);
        $pw1 = strip_tags($_POST['pw1']);
        $pw2 = strip_tags($_POST['pw2']);
        if ($pw1 != $pw2) {
            header("Location: index.php?method=register&error=pwm");
            die();
        }
        $res = $db->simpleQuery("SELECT * FROM users WHERE email='" . $db->escape($email) . "' LIMIT 1");
        if (!$res) {
            header("Location: index.php?method=register&error=internal");
            die();
        }
        if ($res->num_rows >= 1) {
            header("Location: index.php?method=register&error=regi");
            die();
        }
        $id = bin2hex((openssl_random_pseudo_bytes(23)));
        $db->prepareQuery("INSERT INTO users (id, email, firstname, lastname, password) VALUES (?, ?, ?, ?, ?)", array(
            $db->escape($id), $db->escape($email), $db->escape($firstname), $db->escape($lastname), $db->escape(password_hash($pw1, PASSWORD_DEFAULT))
        ));
        $db->prepareQuery("INSERT INTO notifications (userid, message) VALUES (?, ?)", array(
            $db->escape($id), $db->escape("Welcome, " . $firstname . " " . $lastname . "!")
        ));
        $db->prepareQuery("INSERT INTO balances (userid, balance) VALUES (?, ?)", array(
            $db->escape($id), $db->escape("0.00")
        ));

        $token = $db->generateRandomString(5);

        $text = 'Hi, Thank you for registering. In Order to access your Dashboard, you need to confirm your account. Paste the code below on the website nd continue.
<br><br>Here is your registration code:<br>
            ' . $token . '<br><br><br>Best Regards, KIS Developer Team';
        $db->mail($email, 'Confirmation Token', $text);

        $db->prepareQuery("INSERT INTO vertification_tokens (usermail, token) VALUES (?, ?)", array(
            $db->escape($email), $db->escape($token)
        ));

        header("Location: confirm.php");
    } else {
        header("Location: index.php?method=register&error=fill");
        die();
    }
}


?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon.png"/>
    <link rel="icon" type="image/png" href="assets/favicon.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title><?= $db->getConfig()['site_name']; ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
    <meta charset="utf-8"/>
    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <!--  Material Dashboard CSS    -->
    <link href="assets/css/material-dashboard.css?v=1.2.0" rel="stylesheet"/>
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet"/>
    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet'
          type='text/css'>
    <link href="assets/css/style.css" rel="stylesheet"/>
</head>

<body>
<?php if ($method == 'login'): ?>
    <div class="row-fluid">
        <div class="col-md-4 col-md-offset-4">
            <form action="index.php?method=login&continue_login=true&token=<?= $_SESSION['csrftoken']?><?= isset($_GET['return']) ? "&return=" . $_GET['return'] : ""; ?>"
                  method="post"
                  class="navbar-form navbar-left form-signin">
                <h3 class="form-signin-heading"><?= $db->m("login_header") ?></h3>
                <hr class="colorgraph">
                <?php
                if (isset($_GET['error'])) {
                    switch ($_GET['error']) {
                        case 'fill':
                            echo "Please fill every field!";
                            break;
                        case 'wrong':
                            echo "Wrong user or password";
                            break;
                        case 'internal':
                            echo "Internal error! We are working on it!";
                            break;
                        default:
                            echo "Unknown Error";
                            break;
                    }
                }
                ?>
                <br>
                <input type="text" value="" name="email" placeholder="<?= $db->m("login_email") ?>" class="form-control"
                       autofocus=""
                       required/>
                <input type="password" value="" name="pw" placeholder="<?= $db->m("login_password") ?>" class="form-control" required/>
                <div class="form-check pull-left" style="padding-bottom: 15px; font-size: 1.6em;">
                    <input class="form-check-input" name="stay" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        <?= $db->m("login_stay") ?>
                    </label>
                </div>
                <button type="submit" value="login" name="Submit" class="btn btn-lg btn-primary btn-block"/>
                <?= $db->m("login_header") ?></button>
                <a href="index.php?method=register"><?= $db->m("login_button") ?></a>
            </form>
        </div>
    </div>
<?php elseif ($method == 'register'): ?>
    <div class="row-fluid">
        <div class="col-md-4 col-md-offset-4">
            <form action="index.php?method=register&continue_registration=true<?= "&token=" . $_SESSION['csrftoken'] ?>" method="post"
                  class="navbar-form navbar-left form-signin">
                <h3 class="form-signin-heading"><?= $db->m("login_button") ?></h3>
                <hr class="colorgraph">
                <?php
                if (isset($_GET['error'])) {
                    switch ($_GET['error']) {
                        case 'fill':
                            echo $db->e(1);
                            break;
                        case 'wrong':
                            echo $db->e(2);
                            break;
                        case 'internal':
                            echo $db->e(3);
                            break;
                        case 'pwm':
                            echo $db->e(4);
                            break;
                        case 'regi':
                            echo $db->e(5);
                            break;
                        default:
                            echo "Unknown Error";
                            break;
                    }
                }
                ?>
                <br>
                <input type="text" value="" name="firstname" placeholder="<?= $db->m("login_firstname") ?>" class="form-control"
                       required/>
                <input type="text" value="" name="lastname" placeholder="<?= $db->m("login_lastname") ?>" class="form-control"
                       required/>
                <br>
                <input type="text" value="" name="email" placeholder="<?= $db->m("login_email") ?>" align="left"
                       class="form-control"
                       required/>
                <br>
                <input type="password" value="" name="pw1" placeholder="<?= $db->m("login_password") ?>" class="form-control"
                       required/>
                <input type="password" value="" name="pw2" placeholder="<?= $db->m("login_repeat") ?>" class="form-control"
                       required/>
                <br>
                <input type="submit" value="<?= $db->m("login_button") ?>" class="btn btn-primary"/>
                <br>
                <a href="index.php?method=login"><?= $db->m("login_header") ?></a>
            </form>
        </div>
    </div>
<?php endif; ?>
</body>
<!--   Core JS Files   -->
<script src="assets/js/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/material.min.js" type="text/javascript"></script>
<!--  Charts Plugin -->
<script src="assets/js/chartist.min.js"></script>
<!--  Dynamic Elements plugin -->
<script src="assets/js/arrive.min.js"></script>
<!--  PerfectScrollbar Library -->
<script src="assets/js/perfect-scrollbar.jquery.min.js"></script>
<!-- Material Dashboard javascript methods -->
<script src="assets/js/material-dashboard.js?v=1.2.0"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->


</html>
