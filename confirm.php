<html>
<?php

require 'php/database.php';
$db = new DB();


if (isset($_GET['action'])) {
    if ($_GET['action'] == 'resend') {
        if (isset($_POST['email'])) {
            $email = $db->getConnection()->escape_string($_POST['email']);
            // Resend Email
            $res = $db->simpleQuery("SELECT * FROM vertification_tokens WHERE usermail='" . $email . "' LIMIT 1");
            if ($res) {
                if ($res->num_rows == 0) {
                    echo "No Confirmation tokens found for that account!";
                } else {
                    $row = $res->fetch_object();
                    $text = 'Hi, Thank you for registering. In Order to access your Dashboard, you need to confirm your account. Paste the code below on the website nd continue.
<br><br>Here is your registration code:<br>
            ' . $row->token . '<br><br><br>Best Regards, KIS Developer Team';
                    $db->mail($email, "Confirmation Token", $text);
                }
            }
        }
    }
}
?>
<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon.png"/>
    <link rel="icon" type="image/png" href="assets/favicon.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Confirmation</title>
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
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet'
          type='text/css'>
    <link href="assets/css/style.css" rel="stylesheet"/>
</head>

<body>
<div class="row-fluid">
    <?php
    if (isset($_POST['email']) && isset($_POST['token'])) {
        $email = $db->getConnection()->escape_string($_POST['email']);
        $token = $db->getConnection()->escape_string($_POST['token']);

        $res = $db->simpleQuery("SELECT * FROM vertification_tokens WHERE usermail='" . $email . "' AND token='" . $token . "' LIMIT 1");
        if ($res) {
            if ($res->num_rows == 0) {
                echo "Wrong confirmation key!";
            } else {
                $db->simpleQuery("UPDATE users SET verified=1 WHERE email='" . $email . "'");
                header("Location: index.php");
            }
        }
    }
    ?>
    <div class="col-md-4 col-md-offset-4">
        <form class="navbar-form navbar-left form-signin" method="post" action="confirm.php">
            <h3 class="form-signin-heading">Confirmation</h3>
            <hr class="colorgraph">
            <br>
            <input type="text" value="" name="email" placeholder="email" class="form-control" autofocus="" required/>
            <br>
            <input type="text" value="" name="token" maxlength="5" placeholder="Confirmation key" class="form-control"
                   autofocus="" required/>
            <button type="submit" value="confirm" name="Submit" class="btn btn-lg btn-primary btn-block"/>
            Confirm!</button>
        </form>
    </div>
    <div class="col-md-4 col-md-offset-4">
        <form class="navbar-form navbar-left form-signin" method="post" action="confirm.php?action=resend">
            <h3 class="form-signin-heading">Resend Mail</h3>
            <hr class="colorgraph">
            <br>
            <input type="text" value="" name="email" placeholder="email" class="form-control" autofocus="" required/>
            <br>
            <button type="submit" value="confirm" name="Submit" class="btn btn-lg btn-primary btn-block"/>
            Resend!</button>
        </form>
    </div>
</div>
</body>

</html>
