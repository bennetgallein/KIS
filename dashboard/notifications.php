<?php

$res = $db->simpleQuery("SELECT * FROM notifications WHERE userid='" . $user->getId() . "' AND isread = 0 ORDER BY inserted DESC");
?>
<div class="dropdown">
    <a class="dropdown-toggle nocaretdrop" href="#" role="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-bell mdi-light mdi-24px"></i><span class="badge badge-danger" style="margin-left: -10px;"><?= $res->num_rows > 0 ? $res->num_rows : ""?></span></a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notification" style="right: 0; left: auto;">

        <?php
        if ($res->num_rows == 0) {
            echo "<a class='dropdown-item' href='#'>" . $db->m("notifications_nonew") . "</a>";
        }
        while ($row = $res->fetch_object()) {
            $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            echo '<a class="dropdown-item" href="index.php?removenotification=1&id=' . $row->id . '&return=' . $actual_link . '">' . $row->message . '</a>';
        }
        ?>
    </div>
</div>
<a href="user.php" class="ml-3"><i class="mdi mdi-account mdi-light mdi-24px"></i></a>
