<?php
$res = $db->simpleQuery("SELECT * FROM notifications WHERE userid='" . $user->getId() . "' AND isread = 0");

?>
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
        if ($row == 0):
            echo '<li><a>No new notifications!</a></li>';
        endif;
        ?>
        <?php

        while ($row = $res->fetch_object()) {
            echo '<li><a>' . $row->message . '</a></li>';
        }

        ?>
    </ul>
</li>