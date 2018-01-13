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

}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon.png"/>
    <link rel="icon" type="image/png" href="assets/favicon.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title><?php echo $db->getConfig()['site_name']; ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
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
                <h3 class="form-signin-heading"><?= $db->gM(0)?></h3>
                <hr class="colorgraph">
                <br>
                <input type="text" value="" name="Email" placeholder="<?= $db->gM(1)?>" class="form-control" autofocus=""
                       required/>
                <input type="text" value="" name="Password" placeholder="<?= $db->gM(2) ?>>" class="form-control" required/>
                <button type="submit" value="<?= $db->gM(3)?>" name="Submit" class="btn btn-lg btn-primary btn-block"/>
                Login</button>
                <a href="index.php?method=register"><?= $db->gM(4)?></a>
            </form>
        </div>
    </div>
    <?php elseif ($method == 'register'): ?>
    <div class="row-fluid">
        <div class="col-md-4 col-md-offset-4">
            <form action="index.php?method=register&continue_registrationc=true" method="post"
                  class="navbar-form navbar-left form-signin">
                <h3 class="form-signin-heading"><?= $db->gM(5)?></h3>
                <hr class="colorgraph">
                <br>
                <input type="text" value="" name="firstname" placeholder="<?= $db->gM(6)?>" class="form-control" required/>
                <input type="text" value="" name="lastname" placeholder="<?= $db->gM(7)?>" class="form-control" required/>
                <br>
                <input type="text" value="" name="email" placeholder="<?= $db->gM(8)?>" align="left" class="form-control" required/>
                <br>
                <input type="text" value="" name="pw1" placeholder="<?= $db->gM(9)?>" class="form-control" required/>
                <input type="text" value="" name="pw2" placeholder="<?= $db->gM(10)?>" class="form-control" required/>
                <br>
                <input type="submit" value="Register" placeholder="<?= $db->gM(11)?>" class="btn btn-primary"/>
                <br>
                <a href="index.php?method=login"><?= $db->gM(12)?></a>
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
