<?php
include("../php/database.php");
$db = new DB();

if (file_exists(dirname(__FILE__) . "/../config.json")) {
    $config = json_decode(file_get_contents(dirname(__FILE__) . "/../config.json"), true);
    if ($db->getConnection()->connect_errno == 1049):
        ?>
        <form action="setup_database.php" method="get">
            <input type="text" name="license" placeholder="License Key" />
            <input type="submit"/>
        </form>
        <?php
    else:
        header("Location: ../index.php");
    endif;
}
