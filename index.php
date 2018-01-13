<?php
if (!(isset($_GET['method']))) {
    header("Location: index.php?method=login");
    die();
}
if (!($_GET['method'] == 'login' || $_GET['method'] == 'register')) {
    header("Location: index.php?method=login");
    die();
}
$method = $_GET['method'];

// $_GET['continue_registration] == true ==> registrierung
// $_GET['continue_login] == true ==> login
require 'php/database.php';
$db = new DB();
if (isset($_GET['continue_login']) && isset($_GET['method'])) {
    if (!(isset($_POST['email']) && isset($_POST['pw']))) {
        header("Location: index.php?method=login&error=fill");
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
    if ($res->num_rows < 1) {
        header("Location: index.php?method=login&error=wrong");
    }
    $data = $res->fetch_object();
    $arr = array(
        "realid" => $data->_id,
        "id" => $data->id,
        "email" => $data->email,
        "firstname" => $data->firstname,
        "lastname" => $data->lastname,
        "password" => $data->password
    );
    include("php/User.php");
    $user = new User($arr);
    //set cookies & proceed login
    $_SESSION['user'] = $user;
    if (isset($_POST['stay'])) {

    }
}
if (isset($_GET['continue_registration']) && isset($_GET['method'])) {
    if (!(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['pw1']) && isset($_POST['pw2']))) {
        header("Location: index.php?method=register&error=fill");
        die();
    }
    if (!($_GET['method'] == "register")) {
        header("Location: index.php");
        die();
    }
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $pw1 = $_POST['pw1'];
    $pw2 = $_POST['pw2'];
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
        $db->escape($id), $db->escape($email), $db->escape($firstname), $db->escape($lastname), $db->escape(md5($pw1))
    ));
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
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <!--  Material Dashboard CSS    -->
    <link href="assets/css/material-dashboard.css?v=1.2.0" rel="stylesheet"/>
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet"/>
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet'
          type='text/css'>
    <link href="assets/css/style.css" rel="stylesheet"/>
</head>

<body>
<?php if ($method == 'login'): ?>
    <div class="row-fluid">
        <div class="col-md-4 col-md-offset-4">
            <form action="index.php?method=login&continue_login=true" method="post"
                  class="navbar-form navbar-left form-signin">
                <h3 class="form-signin-heading"><?= $db->m(0) ?></h3>
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
                        default:
                            echo "Unknown Error";
                            break;
                    }
                }
                ?>
                <br>
                <input type="text" value="" name="email" placeholder="<?= $db->m(1) ?>" class="form-control"
                       autofocus=""
                       required/>
                <input type="password" value="" name="pw" placeholder="<?= $db->m(2) ?>>" class="form-control" required/>
                <div class="form-check pull-left" style="padding-bottom: 15px; font-size: 1.6em;">
                    <input class="form-check-input" name="stay" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        <?= $db->m(13)?>
                    </label>
                </div>
                <button type="submit" value="<?= $db->m(3) ?>" name="Submit" class="btn btn-lg btn-primary btn-block"/>
                Login</button>
                <a href="index.php?method=register"><?= $db->m(4) ?></a>
            </form>
        </div>
    </div>
<?php elseif ($method == 'register'): ?>
    <div class="row-fluid">
        <div class="col-md-4 col-md-offset-4">
            <form action="index.php?method=register&continue_registration=true" method="post"
                  class="navbar-form navbar-left form-signin">
                <h3 class="form-signin-heading"><?= $db->m(5) ?></h3>
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
                <input type="text" value="" name="firstname" placeholder="<?= $db->m(6) ?>" class="form-control"
                       required/>
                <input type="text" value="" name="lastname" placeholder="<?= $db->m(7) ?>" class="form-control"
                       required/>
                <br>
                <input type="text" value="" name="email" placeholder="<?= $db->m(8) ?>" align="left"
                       class="form-control"
                       required/>
                <br>
                <input type="password" value="" name="pw1" placeholder="<?= $db->m(9) ?>" class="form-control" required/>
                <input type="password" value="" name="pw2" placeholder="<?= $db->m(10) ?>" class="form-control" required/>
                <br>
                <input type="submit" value="Register" placeholder="<?= $db->m(11) ?>" class="btn btn-primary"/>
                <br>
                <a href="index.php?method=login"><?= $db->m(12) ?></a>
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
<!--  Notifications Plugin    -->
<script src="assets/js/bootstrap-notify.js"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!-- Material Dashboard javascript methods -->
<script src="assets/js/material-dashboard.js?v=1.2.0"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // Javascript method's body can be found in assets/js/demos.js
        demo.initDashboardPageCharts();
    });
</script>

</html>
