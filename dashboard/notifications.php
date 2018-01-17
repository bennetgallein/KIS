<?php
$res = $db->simpleQuery("SELECT * FROM notifications WHERE userid='" . $user->getId() . "' AND isread = 0");

?>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="material-icons">notifications</i>
        <span class="notification"><?= $res->num_rows ?></span>
        <p class="hidden-lg hidden-md">Notifications</p>
    </a>
    <ul class="dropdown-menu">
        <?php

        while ($row = $res->fetch_object()) {
            echo '<li><a>' . $row->message . '</a></li>';
        }

        ?>
    </ul>
</li>