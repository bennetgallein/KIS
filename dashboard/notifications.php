<?php

$res = $db->simpleQuery("SELECT * FROM notifications WHERE userid='" . $user->getId() . "' AND isread = 0 ORDER BY inserted DESC");
?>
<style>
    .dropdown-menu li a:hover, .dropdown-menu li a:focus, .dropdown-menu li a:active {
        background-color: #288FEB !important;
    }
    .dropdown-menu .lidrop a:hover, .dropdown-menu .lidrop a:focus, .dropdown-menu .lidrop a:active {
        background-color: #FFFFFF !important;
        color: #000 !important;
        box-shadow: none !important;
    }
</style>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="material-icons">notifications</i>
        <?php
        if ($res->num_rows > 0):
            ?>
            <span class="notification"><?= $res->num_rows ?></span>
            <p class="hidden-lg hidden-md">Notifications</p>
        <?php
        endif;
        ?>
    </a>
    <ul class="dropdown-menu">
        <?php
        if ($res->num_rows == 0)
            echo '<li class="lidrop"><a>No new notifications!</a></li>';

        while ($row = $res->fetch_object()) {
            $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            echo '<li><a href="index.php?removenotification=1&id=' . $row->id . '&return=' . $actual_link . '">' . $row->message . ' <i class="material-icons" style="cursor: pointer">highlight_off</i></a></li>';
        }

        ?>
    </ul>
</li>